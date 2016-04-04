<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\StudentId;
use Codeup\DataBuilders\A as An;
use DateTime;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Underscore\Types\Arrays;

class AttendanceGeneratorCommand extends Command
{
    /** @var Connection */
    private $connection;

    /** @var Attendances */
    private $attendances;

    /**
     * @param Connection $connection
     * @param Attendances $attendances
     */
    public function __construct(
        Connection $connection,
        Attendances $attendances
    ) {
        parent::__construct();
        $this->connection = $connection;
        $this->attendances = $attendances;
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
        $bootcamps = $this->currentBootcamps(new DateTime('now'));
        $absentStudents = $this->absentStudentsIn($bootcamps);
        $students = $this->chooseRandom($absentStudents);
        $this->showAttendanceToBeGenerated($output, $students);
        $this->generateAttendances($students);
    }

    /**
     * @param DateTime $today
     * @return array
     */
    protected function currentBootcamps(DateTime $today)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->addSelect('b.bootcamp_id')
            ->from('bootcamps', 'b')
            ->where('b.start_date <= :today AND :today <= b.stop_date')
            ->setParameter('today', $today->format('Y-m-d'))
        ;

        return Arrays::pluck($builder->execute()->fetchAll(), 'bootcamp_id');
    }

    /**
     * @param array $bootcamps
     * @return array
     */
    protected function absentStudentsIn(array $bootcamps)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->addSelect('b.bootcamp_id')
            ->addSelect('b.start_time')
            ->addSelect('b.cohort_name')
            ->addSelect('s.student_id')
            ->addSelect('a.attendance_id')
            ->from('bootcamps', 'b')
            ->innerJoin('b', 'students', 's', 'b.bootcamp_id = s.bootcamp_id')
            ->leftJoin(
                's',
                'attendances',
                'a',
                's.student_id = a.student_id AND DATE(a.date) = :today AND a.type = :check_in'
            )
            ->where('b.bootcamp_id IN (' . implode(', ', $bootcamps) . ')')
        ;

        return Arrays::group(
            Arrays::filter($builder->execute()->fetchAll(), function ($row) {
                return is_null($row['attendance_id']);
            }),
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
     */
    protected function generateAttendances(array $randomStudents)
    {
        foreach ($randomStudents as $studentsInBootcamp) {
            $this->attendanceForBootcamo($studentsInBootcamp);
        }
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
     * @param $students
     */
    protected function attendanceForBootcamo($students)
    {
        foreach ($students as $student) {
            $startTime = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $student['start_time']
            );
            $minutes = mt_rand(-90, 90);
            $startTime->modify("$minutes minutes");
            $attendance = An::attendance()
                ->withId($this->attendances->nextAttendanceId()->value())
                ->withStudentId(StudentId::fromLiteral($student['student_id']))
                ->recordedAt($startTime)
                ->build();
            $this->attendances->add($attendance);
        }
    }
}
