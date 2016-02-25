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
    private $now;

    /** @var Students */
    private $students;

    /** @var Bootcamps */
    private $bootcamps;

    /** @var MacAddress[] */
    private $knownAddresses;

    /** @var MacAddress */
    private $unknownAddresses;

    /**
     * @return Students
     */
    abstract public function studentsInstance();

    abstract public function bootcampsInstance();

    /** @before */
    function generateFixtures()
    {
        $this->now = (new DateTime('now'))->setTime(12, 0, 0);
        $this->students = $this->studentsInstance();
        $this->bootcamps = $this->bootcampsInstance();
        $this->bootcamps->add($bootcamp = A::bootcamp()->build());
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
            ->whoCheckedInAt($this->now->sub(new DateInterval('PT1H')))
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
    function it_should_not_find_students_if_mac_addresses_are_unknown()
    {
        $students = $this->students->attending(
            $this->now,
            $this->unknownAddresses
        );

        $this->assertCount(0, $students);
    }

    /** @test */
    function it_should_find_students_that_match_with_a_known_mac_address()
    {
        $students = $this->students->attending(
            $this->now,
            array_merge($this->unknownAddresses, $this->knownAddresses)
        );

        $this->assertCount(3, $students);
    }

    /** @test */
    function it_should_not_find_students_if_bootcamp_is_over()
    {
        $this->students->add(A::student()
            ->enrrolledInABootcampAlreadyFinished()
            ->withMacAddress($this->knownAddresses[0])
            ->build()
        );
        $students = $this->students->attending(
            $today = new DateTime(),
            array_merge($this->unknownAddresses, $this->knownAddresses)
        );

        $this->assertCount(3, $students);
    }
}
