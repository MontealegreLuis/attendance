<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Attendance\DoRollCall;
use Codeup\Bootcamps\Student;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollCallCommand extends Command
{
    private $useCase;

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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $students = $this->useCase->rollCall();
        $output->writeln(sprintf(
            '<info>%d new student(s) found.</info>', count($students)
        ));
        $table = new Table($output);
        $table
            ->setHeaders(['Student'])
            ->setRows([
                array_map(function(Student $student) {
                    return "{$student->name()} - {$student->address()}";
                }, $students)
            ])
        ;
        $table->render();
    }


}
