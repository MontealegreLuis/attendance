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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
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
            if ($this->databaseExists($parameters, $connection)) {
                $this->doNotCreateDatabase($output, $parameters);
            } else {
                $this->createDatabase($output, $connection, $parameters);
            }
        } catch (Exception $e) {
            $this->cannotCreateDatabase($output, $parameters, $e);
        }

        $connection->close();
    }

    /**
     * @param array $parameters
     * @return array
     */
    private function withoutDatabaseName(array $parameters)
    {
        $filtered = $parameters;
        unset($filtered['dbname'], $filtered['path']);

        return $filtered;
    }

    /**
     * @param array $parameters
     * @param Connection $connection
     * @return bool
     */
    private function databaseExists(array $parameters, Connection $connection)
    {
        if ($this->hasPath($parameters)) {
            return file_exists($this->databaseName($parameters));
        }

        return in_array(
            $this->databaseName($parameters),
            $connection->getSchemaManager()->listDatabases()
        );
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
        $name = $connection
            ->getDatabasePlatform()
            ->quoteSingleIdentifier($this->databaseName($parameters))
        ;
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
     * @param array $parameters
     * @return string
     */
    private function databaseName(array $parameters)
    {
        return $this->hasPath($parameters) ? $parameters['path'] : $parameters['dbname'];
    }

    /**
     * @param array $parameters
     * @return bool
     */
    private function hasPath(array $parameters)
    {
        return isset($parameters['path']);
    }
}
