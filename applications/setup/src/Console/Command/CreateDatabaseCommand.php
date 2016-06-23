<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends DatabaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('codeup:db:create')
            ->setDescription('Creates the database')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = $this->getHelper('db')->getConnection()->getParams();
        $connection = DriverManager::getConnection($this->withoutDatabaseName($parameters));

        try {
            $this->createIfExists($output, $parameters, $connection);
        } catch (Exception $e) {
            $this->cannotCreateDatabase($output, $parameters, $e);
        }

        $connection->close();
    }

    /**
     * @param OutputInterface $output
     * @param Connection $connection
     * @param array $parameters
     */
    private function createDatabase(
        OutputInterface $output,
        Connection $connection,
        array $parameters
    ) {
        $name = $this->databaseName($parameters);
        if (!$this->hasPath($parameters)) {
            $name = $connection
                ->getDatabasePlatform()
                ->quoteSingleIdentifier($name)
            ;
        }

        $connection->getSchemaManager()->createDatabase($name);

        $output->writeln(sprintf(
            '<info>Created database <comment>%s</comment></info>',
            $this->databaseName($parameters)
        ));
    }

    /**
     * @param OutputInterface $output
     * @param array $parameters
     */
    protected function doNotCreateDatabase(
        OutputInterface $output,
        array $parameters
    ) {
        $output->writeln(sprintf(
            '<info>Database <comment>%s</comment> already exists.</info>',
            $this->databaseName($parameters)
        ));
    }

    /**
     * @param OutputInterface $output
     * @param array $parameters
     * @param Exception $e
     */
    protected function cannotCreateDatabase(
        OutputInterface $output,
        array $parameters,
        Exception $e
    ) {
        $output->writeln(sprintf(
            '<error>Could not create database <comment>%s</comment></error>',
            $this->databaseName($parameters)
        ));
        $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
    }

    /**
     * @param OutputInterface $output
     * @param array $parameters
     * @param Connection $connection
     */
    private function createIfExists(
        OutputInterface $output,
        array $parameters,
        Connection $connection
    ) {
        if ($this->databaseExists($parameters, $connection)) {
            $this->doNotCreateDatabase($output, $parameters);
        } else {
            $this->createDatabase($output, $connection, $parameters);
        }
    }
}
