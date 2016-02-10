<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\ContractTests;

use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Students;
use Codeup\DataBuilders\A;
use DateTime;
use PHPUnit_Framework_TestCase as TestCase;

//TODO: add test case with students from a bootcamp that has already finished
abstract class StudentsTest extends TestCase
{
    /** @var Students */
    private $students;

    /** @var MacAddress[] */
    private $knownAddresses;

    /** @var MacAddress */
    private $unknownAddresses;

    abstract public function studentsInstance();

    /** @before */
    function generateFixtures()
    {
        $this->students = $this->studentsInstance();
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
        $this->students->add(A::student()->withMacAddress($address1)->build());
        $this->students->add(
            A::student()
                ->whoCheckedInAt(new DateTime('-1 hour'))
                ->withMacAddress($address2)
                ->build()
        );
        $this->students->add(A::student()->withMacAddress($address3)->build());
    }

    /** @test */
    function it_should_not_find_students_if_mac_addresses_are_unknown()
    {
        $students = $this->students->attending(
            $today = new DateTime(),
            $this->unknownAddresses
        );

        $this->assertCount(0, $students);
    }

    /** @test */
    function it_should_find_students_that_match_with_a_known_mac_address()
    {
        $students = $this->students->attending(
            $today = new DateTime(),
            array_merge($this->unknownAddresses, $this->knownAddresses)
        );

        $this->assertCount(3, $students);
    }
}
