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

class PhpServerListener
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

    /**
     * @param ConsoleCommandEvent $event
     */
    public function startPhpServer(ConsoleCommandEvent $event)
    {
        if (
               'codeup:rollcall' === $event->getCommand()->getName()
            && $event->getInput()->getOption('locally')
        ) {
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
        if (
            'codeup:rollcall' === $event->getCommand()->getName()
            && $event->getInput()->getOption('locally')
        ) {
            $this->runner->stopPhpServer();

            $event->getOutput()->writeln(sprintf(
                '<info>PHP server with PID <comment>%d</comment> was stopped</info>',
                $this->runner->phpServerPid()
            ));
        }
    }
}
