<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Attendance\AttendanceAnalyzer;
use Codeup\Attendance\AttendanceGenerator;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Underscore\Types\Arrays;

class AttendanceGeneratorCommand extends Command
{
    /** @var AttendanceAnalyzer */
    private $analyzer;

    /** @var AttendanceGenerator */
    private $generator;

    /**
     * @param AttendanceAnalyzer $analyzer
     * @param AttendanceGenerator $generator
     */
    public function __construct(
        AttendanceAnalyzer $analyzer,
        AttendanceGenerator $generator
    ) {
        parent::__construct();
        $this->analyzer = $analyzer;
        $this->generator = $generator;
    }

    protected function configure()
    {
        $this
            ->setName('codeup:attendance:generate')
            ->setDescription('Generates random attendance entries')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bootcamps = $this->currentBootcamps($today = new DateTime('now'));
        $absentStudents = $this->absentStudentsIn($bootcamps, $today);
        $forRandomStudents = $this->generator->generateRandomAttendance(
            $absentStudents, new DateTime('now')
        );
        $this->showGeneratedAttendance($output, $forRandomStudents);
    }

    /**
     * @param DateTime $today
     * @return array
     */
    protected function currentBootcamps(DateTime $today)
    {
        return Arrays::pluck(
            $this->analyzer->ongoingBootcamps($today),
            'bootcamp_id'
        );
    }

    /**
     * @param array $bootcamps
     * @param DateTime $today
     * @return array
     */
    protected function absentStudentsIn(array $bootcamps, DateTime $today)
    {
        return Arrays::group(
            $this->analyzer->absentStudentsIn($bootcamps, $today),
            'bootcamp_id'
        );
    }

    /**
     * @param OutputInterface $output
     * @param array $randomStudents
     */
    protected function showGeneratedAttendance(
        OutputInterface $output,
        array $randomStudents
    ) {
        foreach ($randomStudents as $students) {
            foreach ($students as $student) {
                $name = $student['cohort_name'];
                break;
            }
            $output->writeln(sprintf(
                'Generating <info>%d</info> attendances for <info>%s</info>...',
                count($students),
                $name
            ));
        }
    }
}
