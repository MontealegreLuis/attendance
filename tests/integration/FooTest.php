<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup;

use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\DomCrawler\Crawler;

class FooTest extends TestCase
{
    /** @test */
    function it_should_find_mac_addresses_entries()
    {
        $crawler = new Crawler(file_get_contents(__DIR__ . '/../fixtures/dhcp_status.html'));
        $crawler
            ->filter('.ipvxtabtable .SpecialTable tr td.tdContentC')
            ->each(function (Crawler $node) {
                if (MacAddress::isValid($node->text())) {
                    var_dump(trim($node->text()));
                }
            })
        ;
    }
}
