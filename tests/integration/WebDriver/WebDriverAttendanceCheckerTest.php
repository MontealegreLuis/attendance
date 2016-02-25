<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\WebDriver;

use PHPUnit_Framework_TestCase as TestCase;

class WebDriverAttendanceCheckerTest extends TestCase
{
    /** @test */
    function it_should_find_mac_addresses_entries()
    {
        $checker = new WebDriverAttendanceChecker(
            require __DIR__ . '/../../../config.php'
        );
        $addresses = $checker->whoIsConnected();

        $this->assertCount(18, $addresses);
    }
}
