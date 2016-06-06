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

class PhpServerListener
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
     * @param ConsoleCommandEvent $event
     */
    public function startPhpServer(ConsoleCommandEvent $event)
    {
        if ($this->isLocalServerNeeded($event)) {
            $this->runner->startPhpServer();

            $event->getOutput()->writeln(sprintf(
                '<info>PHP server is running with PID <comment>%d</comment></info>',
                $this->runner->phpServerPid()
            ));
        }
    }

    /**
     * @param ConsoleTerminateEvent $event
     */
    public function stopPhpServer(ConsoleTerminateEvent $event)
    {
        if ($this->isLocalServerNeeded($event)) {
            $this->runner->stopPhpServer();

            $event->getOutput()->writeln(sprintf(
                '<info>PHP server with PID <comment>%d</comment> was stopped</info>',
                $this->runner->phpServerPid()
            ));
        }
    }

    /**
     * PHP built-in server is only useful for the `codeup:rollcall` command when
     * the option `locally` is provided
     *
     * @param ConsoleEvent $event
     * @return bool
     */
    private function isLocalServerNeeded(ConsoleEvent $event)
    {
        return in_array($event->getCommand()->getName(), $this->validCommands)
            && $event->getInput()->getOption('locally');
    }
}
