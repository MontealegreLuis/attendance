<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command\Listeners;

use Codeup\TestHelpers\HeadlessRunner;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

/**
 * Start and stop PhantomJS before and after executing command `codeup:rollcall`
 */
class PhantomJsListener
{
    /** @var HeadlessRunner */
    private $runner;

    /**
     * @param HeadlessRunner $runner
     */
    public function __construct(HeadlessRunner $runner)
    {
        $this->runner = $runner;
    }

    public function startPhantomJs(ConsoleCommandEvent $event)
    {
        if ('codeup:rollcall' !== $event->getCommand()->getName()) {
            return;
        }

        $this->runner->startPhantomJs();

        $event->getOutput()->writeln(sprintf(
            '<info>PhantomJS is running with PID <comment>%d</comment></info>',
            $this->runner->phantomJsPid()
        ));

        sleep(2);
    }

    public function stopPhantomJs(ConsoleTerminateEvent $event)
    {
        if ('codeup:rollcall' !== $event->getCommand()->getName()) {
            return;
        }

        $this->runner->stopPhantomJs();

        $event->getOutput()->writeln(sprintf(
            '<info>PhantomJS with PID <comment>%d</comment> was stopped</info>',
            $this->runner->phantomJsPid()
        ));
    }
}
