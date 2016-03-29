<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\ContractTests;

use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Students;
use Codeup\DataBuilders\A;
use DateInterval;
use DateTime;
use PHPUnit_Framework_TestCase as TestCase;

abstract class StudentsTest extends TestCase
{
    /** @var DateTime */
    private $today;

    /** @var Students */
    private $students;

    /** @var Bootcamps */
    private $bootcamps;

    /** @var MacAddress[] */
    private $knownAddresses;

    /** @var MacAddress[] */
    private $unknownAddresses;

    /**
     * @return Students
     */
    abstract public function studentsInstance();

    abstract public function bootcampsInstance();

    /** @before */
    function generateFixtures()
    {
        $this->today = (new DateTime('now'))->setTime(12, 0, 0);
        $this->students = $this->studentsInstance();
        $this->bootcamps = $this->bootcampsInstance();
        $this->bootcamps->add(
            $bootcamp = A::bootcamp()->notYetFinished($this->today)->build()
        );
        $this->knownAddresses = [
            $address1 = A::macAddress()->build(),
            $address2 = A::macAddress()->build(),
            $address3 = A::macAddress()->build(),
        ];
        $this->unknownAddresses = [
            A::macAddress()->build(),
            A::macAddress()->build(),
            A::macAddress()->build(),
        ];
        $this->students->add(A::student()
            ->enrolledOn($bootcamp)
            ->withMacAddress($address1)
            ->build()
        );
        $this->students->add(A::student()
            ->enrolledOn($bootcamp)
            ->whoCheckedInAt($this->today->sub(new DateInterval('PT1H')))
            ->withMacAddress($address2)
            ->build()
        );
        $this->students->add(A::student()
            ->enrolledOn($bootcamp)
            ->withMacAddress($address3)
            ->build()
        );
    }

    /** @test */
    function it_does_not_find_students_if_mac_addresses_are_unknown()
    {
        $students = $this->students->attending(
            $this->today,
            $this->unknownAddresses
        );

        $this->assertCount(0, $students);
    }

    /** @test */
    function it_finds_students_with_a_known_mac_address()
    {
        $students = $this->students->attending(
            $this->today,
            array_merge($this->unknownAddresses, $this->knownAddresses)
        );

        $this->assertCount(3, $students);
    }

    /** @test */
    function it_does_not_find_students_if_bootcamp_is_over()
    {
        $this->students->add(A::student()
            ->enrolledOn(
                A::bootcamp()
                    ->alreadyFinished($this->today)
                    ->build()
            )
            ->withMacAddress($this->knownAddresses[0])
            ->build()
        );
        $students = $this->students->attending(
            $this->today,
            array_merge($this->unknownAddresses, $this->knownAddresses)
        );
        $this->assertCount(3, $students);
    }
}
