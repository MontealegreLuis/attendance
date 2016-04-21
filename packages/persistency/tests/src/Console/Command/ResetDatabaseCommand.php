<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResetDatabaseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('codeup:db:reset')
            ->setDescription('Recreates the database')
            ->setHelp(<<<HELP
Recreates the database

<info>bin/setup codeup:db:reset</info>

It also seeds the database if the option <info>seed</info> is passed:

<info>bin/setup codeup:db:reset -s</info>
HELP
            )
            ->addOption(
                'seed',
                's',
                InputOption::VALUE_NONE,
                'Seed the database with fake information'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('codeup:db:drop');
        $command->run(new ArrayInput(['command' => 'codeup:db:drop']), $output);

        $command = $this->getApplication()->find('codeup:db:create');
        $command->run(new ArrayInput(['command' => 'codeup:db:create']), $output);

        $command = $this->getApplication()->find('migrations:migrate');
        $migrationInput = new ArrayInput(['command' => 'migrations:migrate']);
        $migrationInput->setInteractive(false);
        $command->run($migrationInput, $output);

        if ($input->getOption('seed')) {
            $command = $this->getApplication()->find('codeup:db:seed');
            $command->run(
                new ArrayInput(['command' => 'codeup:db:seed']),
                $output
            );
        }
    }
}
