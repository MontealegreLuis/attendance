<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit_Framework_TestCase as TestCase;

class WebDriverAttendanceCheckerTest extends TestCase
{
    /** @test */
    function it_should_find_mac_addresses_entries()
    {
        $options = require __DIR__ . '/../../../config.dist.php';
        $checker = new WebDriverAttendanceChecker(
            RemoteWebDriver::create(
                $options['webdriver']['host'],
                $options['webdriver']['capabilities'],
                $options['webdriver']['timeout']
            ),
            $options['dhcp']
        );
        $addresses = $checker->whoIsConnected();

        $this->assertCount(63, $addresses);
    }
}
