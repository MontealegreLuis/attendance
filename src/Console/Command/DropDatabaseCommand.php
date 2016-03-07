<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Doctrine\DBAL\DriverManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DropDatabaseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('codeup:db:drop')
            ->setDescription('Drops the database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $this->getHelper('db')->getConnection()->getParams();
        $hasPath = isset($options['path']);
        $name = $hasPath ? $options['path']: $options['dbname'];
        unset($options['dbname'], $options['path']);
        $connection = DriverManager::getConnection($options);

        if (!$hasPath) {
            $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
            $databaseExists = in_array(
                $name,
                $connection->getSchemaManager()->listDatabases()
            );
        } else {
            $databaseExists = file_exists($name);
        }

        try {
            if ($databaseExists) {
                $connection->getSchemaManager()->dropDatabase($name);
                $output->writeln(sprintf(
                    '<info>Dropped database <comment>%s</comment></info>',
                    $name
                ));
            } else {
                $output->writeln(sprintf(
                    '<info>Database <comment>%s</comment> doesn\'t exist. Skipped.</info>',
                    $name
                ));
            }
        } catch (Exception $e) {
            $output->writeln(sprintf(
                '<error>Could not drop database ,<comment>%s</comment></error>',
                $name
            ));
            $output->writeln(sprintf(
                '<error>%s</error>',
                $e->getMessage()
            ));
        }
    }
}
