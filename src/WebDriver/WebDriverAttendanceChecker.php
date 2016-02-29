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
    /** @var array */
    private $options;

    /** @var RemoteWebDriver */
    private $driver;

    /**
     * @param RemoteWebDriver $driver
     * @param string $options
     */
    public function __construct(RemoteWebDriver $driver, $options)
    {
        $this->options = $options;
        $this->driver = $driver;
    }

    /**
     * @return MacAddress[]
     */
    public function whoIsConnected()
    {
        $this->driver->get($this->options['login']);
        $this->driver->wait(3);
        $this->driver
            ->findElement(WebDriverBy::name('username'))
            ->sendKeys($this->options['credentials']['username'])
        ;
        $this->driver
            ->findElement(WebDriverBy::name('password'))
            ->sendKeys($this->options['credentials']['password'])
        ;
        $this->driver->executeScript('SendPassword();');
        $this->driver
            ->findElement(WebDriverBy::name('form_contents'))
            ->submit()
        ;
        $this->driver->get($this->options['page']);
        $this->driver->wait(3);
        $elements = $this->driver
            ->findElements(WebDriverBy::name('DHCPClientStatus'))
        ;

        return MacAddress::addressesFrom($elements[0]->getAttribute('value'));
    }
}
