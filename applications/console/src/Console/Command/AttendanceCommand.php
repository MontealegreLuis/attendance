<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Bootcamps\Student;
use Codeup\Retry\RetriesExhausted;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AttendanceCommand extends Command
{
    /**
     * @param callable $updateAttendance
     * @param OutputInterface $output
     */
    protected function retryToUpdateAttendance(
        callable $updateAttendance,
        OutputInterface $output
    ) {
        try {
            $students = $updateAttendance(new DateTime('now'));
            $this->showAttendanceSummary($students, $output);
        } catch (RetriesExhausted $exception) {
            $this->showRetriesSummary($output, $exception);
        }
    }
    /**
     * @param OutputInterface $output
     * @param RetriesExhausted $exception
     */
    private function showRetriesSummary(
        OutputInterface $output,
        RetriesExhausted $exception
    ) {
        $output->writeln(
            '<info>Could not complete command, retries exhausted.</info>'
        );
        $output->writeln(sprintf(
            '<info>Following errors occured:<error>%s</error></info>',
            $exception->getMessage()
        ));
    }

    /**
     * @param array $students
     * @param OutputInterface $output
     */
    private function showAttendanceSummary(
        array $students,
        OutputInterface $output
    ) {
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
