<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Goutte;

use Codeup\AttendanceChecker;
use Codeup\MacAddress;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class GoutteAttendanceChecker extends Client implements AttendanceChecker
{
    /** @var string */
    private $url;

    /**
     * @param string $attendanceUrl
     */
    public function __construct($attendanceUrl)
    {
        parent::__construct();
        $this->url = $attendanceUrl;
    }

    /**
     * @return MacAddress[]
     */
    public function whoIsConnected()
    {
        $addresses = [];
        $crawler = $this->request('GET', $this->url);

        $crawler
            ->filter('.ipvxtabtable .SpecialTable tr td.tdContentC')
            ->each(function (Crawler $node) use (&$addresses) {
                if (MacAddress::isValid($node->text())) {
                    $addresses[] = MacAddress::withValue(trim($node->text()));
                }
            })
        ;

        return $addresses;
    }
}
