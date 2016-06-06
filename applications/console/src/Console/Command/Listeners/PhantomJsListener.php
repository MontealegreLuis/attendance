<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command\Listeners;

use Codeup\TestHelpers\HeadlessRunner;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

/**
 * Start and stop PhantomJS before and after executing command `codeup:rollcall`
 */
class PhantomJsListener
{
    /** @var array */
    private $validCommands = [
        'codeup:rollcall',
        'codeup:checkout',
    ];

    /** @var HeadlessRunner */
    private $runner;

    /**
     * @param HeadlessRunner $runner
     */
    public function __construct(HeadlessRunner $runner)
    {
        $this->runner = $runner;
    }

    /**
     * Start PhantomJS only for the command `codeup:rollcall`
     *
     * @param ConsoleCommandEvent $event
     */
    public function startPhantomJs(ConsoleCommandEvent $event)
    {
        if (!$this->requiresPhantomJs($event)) {
            return;
        }

        $this->runner->startPhantomJs();

        $event->getOutput()->writeln(sprintf(
            '<info>PhantomJS is running with PID <comment>%d</comment></info>',
            $this->runner->phantomJsPid()
        ));

        sleep(2);
    }

    /**
     * Stop PhantomJS only for the command `codeup:rollcall`
     *
     * @param ConsoleTerminateEvent $event
     */
    public function stopPhantomJs(ConsoleTerminateEvent $event)
    {
        if (!$this->requiresPhantomJs($event)) {
            return;
        }

        $this->runner->stopPhantomJs();

        $event->getOutput()->writeln(sprintf(
            '<info>PhantomJS with PID <comment>%d</comment> was stopped</info>',
            $this->runner->phantomJsPid()
        ));
    }

    /**
     * @param ConsoleEvent $event
     * @return bool
     */
    private function requiresPhantomJs(ConsoleEvent $event)
    {
        return in_array($event->getCommand()->getName(), $this->validCommands);
    }
}
