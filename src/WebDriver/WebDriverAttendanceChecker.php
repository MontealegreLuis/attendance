<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\WebDriver;

use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\MacAddress;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;

class WebDriverAttendanceChecker implements AttendanceChecker
{
    /** @var string */
    private $options;

    /**
     * @param string $options
     */
    public function __construct($options)
    {
        parent::__construct();
        $this->options = $options;
    }

    /**
     * @return MacAddress[]
     */
    public function whoIsConnected()
    {
        $addresses = [];

        $driver = RemoteWebDriver::create(
            $this->options['webdriver']['host'],
            $this->options['webdriver']['capabilities'],
            $this->options['webdriver']['timeout']
        );

        $driver->get($this->options['login']);
        $driver->wait(3);
        $driver
            ->findElement(WebDriverBy::name('username'))
            ->sendKeys($this->options['credentials']['username'])
        ;
        $driver
            ->findElement(WebDriverBy::name('password'))
            ->sendKeys($this->options['credentials']['password'])
        ;
        $driver->executeScript('SendPassword();');
        $driver->findElement(WebDriverBy::name('form_contents'))->submit();
        $driver->get($this->options['page']);
        $driver->wait(3);
        $elements = $driver->findElements(WebDriverBy::cssSelector(
            '.ipvxtabtable .SpecialTable tr td.tdContentC'
        ));

        array_map(function (RemoteWebElement $element) use (&$addresses) {
            if (MacAddress::isValid($element->getText())) {
                $addresses[] = MacAddress::withValue(trim($element->getText()));
            }
        }, $elements);

        return $addresses;
    }
}
