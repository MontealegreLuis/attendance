<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command\Listeners;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

/**
 * Start and stop PhantomJS before and after executing command `codeup:rollcall`
 */
class PhantomJsListener
{
    /** @var int */
    private $pidPhantomJs;

    public function startPhantomJs(ConsoleCommandEvent $event)
    {
        if ('codeup:rollcall' !== $event->getCommand()->getName()) {
            return;
        }

        $output = [];
        exec(
            'phantomjs --webdriver=127.0.0.1:8910 >/dev/null 2>&1 & echo $!',
            $output
        );
        $this->pidPhantomJs = (int) $output[0];

        $event->getOutput()->writeln(sprintf(
            '<info>PhantomJS is running with PID <comment>%d</comment></info>',
            $this->pidPhantomJs
        ));

        sleep(2);
    }

    public function stopPhantomJs(ConsoleTerminateEvent $event)
    {
        if ('codeup:rollcall' !== $event->getCommand()->getName()) {
            return;
        }

        exec("kill {$this->pidPhantomJs}");

        $event->getOutput()->writeln(sprintf(
            '<info>PhantomJS with PID <comment>%d</comment> was stopped</info>',
            $this->pidPhantomJs
        ));
    }
}
