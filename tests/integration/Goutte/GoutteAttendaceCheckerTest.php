<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Goutte;

use PHPUnit_Framework_TestCase as TestCase;

class GoutteAttendaceCheckerTest extends TestCase
{
    /** @test */
    function it_should_find_mac_addresses_entries()
    {
        $checker = new GoutteAttendanceChecker('http://localhost:8000/dhcp_status.html');
        $addresses = $checker->whoIsConnected();

        $this->assertCount(18, $addresses);
    }
}
