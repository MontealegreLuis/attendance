<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Attendance\DoRollCall;
use Codeup\Bootcamps\Student;
use DateTime;
use Retry\BackOff\ExponentialBackOffPolicy;
use Retry\Policy\SimpleRetryPolicy;
use Retry\RetryProxy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollCallCommand extends Command
{
    /** @var DoRollCall */
    private $useCase;

    /**
     * @param DoRollCall $rollCall
     */
    public function __construct(DoRollCall $rollCall)
    {
        parent::__construct();
        $this->useCase = $rollCall;
    }

    protected function configure()
    {
        $this
            ->setName('codeup:rollcall')
            ->setDescription('Poll the DHCP status page to know who is currently in class')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $retryPolicy = new SimpleRetryPolicy(3);
        $backOffPolicy = new ExponentialBackOffPolicy();

        $proxy = new RetryProxy($retryPolicy, $backOffPolicy);

        $proxy->call([$this, 'rollCall'], [$output]);
    }

    public function rollCall(OutputInterface $output)
    {
        $students = $this->useCase->rollCall(new DateTime('now'));
        $output->writeln(sprintf(
            '<info>%d new student(s) found.</info>', count($students)
        ));
        $table = new Table($output);
        $table
            ->setHeaders(['Student'])
            ->setRows(array_map(function (Student $student) {
                $information = $student->information();
                return ["{$information->name()} - {$information->macAddress()->value()}"];
            }, $students))
        ;
        $table->render();
    }
}
