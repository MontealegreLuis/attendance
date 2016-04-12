<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\StudentHasCheckedIn;
use Codeup\Bootcamps\StudentId;
use Codeup\DataBuilders\A as An;
use Codeup\Dbal\Queries\AbsentStudents;
use Codeup\Dbal\Queries\OngoingBootcamps;
use Codeup\DomainEvents\EventPublisher;
use Codeup\DomainEvents\RecordsEvents;
use DateTime;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Underscore\Types\Arrays;

class AttendanceGeneratorCommand extends Command
{
    use RecordsEvents;

    /** @var Connection */
    private $connection;

    /** @var Attendances */
    private $attendances;

    /** @var EventPublisher */
    private $publisher;

    /**
     * @param Connection $connection
     * @param Attendances $attendances
     * @param EventPublisher $publisher
     */
    public function __construct(
        Connection $connection,
        Attendances $attendances,
        EventPublisher $publisher
    ) {
        parent::__construct();
        $this->connection = $connection;
        $this->attendances = $attendances;
        $this->publisher = $publisher;
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
        $students = $this->chooseRandom($absentStudents);
        $this->showAttendanceToBeGenerated($output, $students);
        $this->generateAttendances($students, $today);
    }

    /**
     * @param DateTime $today
     * @return array
     */
    protected function currentBootcamps(DateTime $today)
    {
        $ongoingBootcamps = new OngoingBootcamps($this->connection);

        return Arrays::pluck($ongoingBootcamps->during($today), 'bootcamp_id');
    }

    /**
     * @param array $bootcamps
     * @param DateTime $today
     * @return array
     */
    protected function absentStudentsIn(array $bootcamps, DateTime $today)
    {
        $absentStudents = new AbsentStudents($this->connection);

        return Arrays::group(
            $absentStudents->enrolledDuring($bootcamps, $today),
            'bootcamp_id'
        );
    }

    /**
     * @param array $absentStudents
     * @return array
     */
    protected function chooseRandom(array $absentStudents)
    {
        $studentsInformation = [];
        foreach ($absentStudents as $bootcampId => $students) {
            shuffle($students);
            $studentsInformation[$bootcampId] = array_slice(
                $students,
                0,
                mt_rand(1, 5)
            );
        }
        return $studentsInformation;
    }

    /**
     * @param array $randomStudents
     * @param DateTime $today
     */
    protected function generateAttendances(
        array $randomStudents,
        DateTime $today
    ) {
        foreach ($randomStudents as $studentsInBootcamp) {
            $this->attendanceForBootcamp($studentsInBootcamp, $today);
        }
        $this->publisher->publish($this->events());
    }

    /**
     * @param OutputInterface $output
     * @param array $students
     */
    protected function showAttendanceToBeGenerated(
        OutputInterface $output,
        array $students
    ) {
        foreach ($students as $studentsByBootcamp) {
            foreach ($studentsByBootcamp as $student) {
                $name = $student['cohort_name'];
                break;
            }
            $output->writeln(sprintf(
                'Generating <info>%d</info> attendances for <info>%s</info>...',
                count($studentsByBootcamp),
                $name
            ));
        }
    }

    /**
     * @param array $students
     * @param DateTime $today
     */
    protected function attendanceForBootcamp(array $students, DateTime $today)
    {
        foreach ($students as $student) {
            $startTime = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $student['start_time']
            );
            $now = clone $today;
            $now->setTime(
                (int) $startTime->format('H'),
                (int) $startTime->format('i'),
                (int) $startTime->format('s')
            );
            $minutes = mt_rand(-90, 90);
            $now->modify("$minutes minutes");
            $attendance = An::attendance()
                ->withId($this->attendances->nextAttendanceId()->value())
                ->withStudentId(StudentId::fromLiteral($student['student_id']))
                ->recordedAt($now)
                ->build();
            $this->attendances->add($attendance);
            $this->recordThat(new StudentHasCheckedIn($attendance->id()));
        }
    }
}
