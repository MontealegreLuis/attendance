<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\PHPUnit;

use Codeup\TestHelpers\HeadlessRunner;
use Exception;
use PHPUnit_Framework_AssertionFailedError as AssertionFailedError;
use PHPUnit_Framework_Test as Test;
use PHPUnit_Framework_TestListener as TestListener;
use PHPUnit_Framework_TestSuite as TestSuite;

class HeadlessBrowserListener implements TestListener
{
    /** @var HeadlessRunner */
    private $runner;

    public function __construct()
    {
        $this->runner = new HeadlessRunner();
    }

    public function addError(Test $test, Exception $e, $time)
    {
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
    }

    public function addIncompleteTest(Test $test, Exception $e, $time)
    {
    }

    public function addRiskyTest(Test $test, Exception $e,$time)
    {
    }

    public function addSkippedTest(Test $test, Exception $e, $time)
    {
    }

    public function startTestSuite(TestSuite $suite)
    {
        if ($suite->getName() === 'headless') {
            $this->runner->startPhpServer();
            echo PHP_EOL, sprintf(
                "PHP server is running with PID %d",
                $this->runner->phpServerPid()
            ), PHP_EOL;

            $this->runner->startPhantomJs();
            echo sprintf(
                "PhantomJS is running with PID %d",
                $this->runner->phantomJsPid()
            ), PHP_EOL;
        }
    }

    public function endTestSuite(TestSuite $suite)
    {
        if ($suite->getName() === 'headless') {
            $this->runner->stopPhpServer();
            echo PHP_EOL, sprintf(
                "Stopped PHP server with PID %d",
                $this->runner->phpServerPid()
            ), PHP_EOL;
            $this->runner->stopPhantomJs();
            echo sprintf(
                "Stopped PhantomJS with PID %d",
                $this->runner->phantomJsPid()
            ), PHP_EOL;
        }
    }

    public function startTest(Test $test)
    {
    }

    public function endTest(Test $test, $time)
    {
    }
}
