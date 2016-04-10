<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command\Listeners;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class PhpServerListener
{
    /** @var int */
    private $pidPhpServer;

    /**
     * @param ConsoleCommandEvent $event
     */
    public function startPhpServer(ConsoleCommandEvent $event)
    {
        if (
               'codeup:rollcall' === $event->getCommand()->getName()
            && $event->getInput()->hasOption('locally')
        ) {
            $output = [];
            exec(
                'php -S localhost:8000 -t tests/fixtures >/dev/null 2>&1 & echo $!',
                $output
            );
            $this->pidPhpServer = (int) $output[0];

            $event->getOutput()->writeln(sprintf(
                '<info>PHP server is running with PID <comment>%d</comment></info>',
                $this->pidPhpServer
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
            && $event->getInput()->hasOption('locally')
        ) {
            exec("kill {$this->pidPhpServer}");

            $event->getOutput()->writeln(sprintf(
                '<info>PHP server with PID <comment>%d</comment> was stopped</info>',
                $this->pidPhpServer
            ));
        }
    }
}
