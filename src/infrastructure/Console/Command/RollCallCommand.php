<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Attendance\DoRollCall;
use Codeup\Bootcamps\Student;
use Codeup\Retry\RetriesExhausted;
use DateTime;
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
        try {

            $students = $this->useCase->rollCall(new DateTime('now'));
            $this->showSummary($students, $output);

        } catch (RetriesExhausted $exception) {
            $output->writeln(
                '<info>Could not complete command, retries exhausted.</info>'
            );
            $output->writeln(sprintf(
                '<info>Following errors occured:<error>%s</error></info>',
                $exception->getMessage()
            ));
        }
    }

    /**
     * @param array $students
     * @param OutputInterface $output
     */
    public function showSummary(array $students, OutputInterface $output)
    {
        $output->writeln(sprintf(
            '<info>%d new student(s) found.</info>', count($students)
        ));

        if (empty($students)) {
            return;
        }

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
