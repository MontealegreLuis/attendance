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

class DropDatabaseCommand extends DatabaseCommand
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
        $parameters = $this->getHelper('db')->getConnection()->getParams();
        $connection = DriverManager::getConnection($this->withoutDatabaseName($parameters));

        try {
            $this->dropIfExists($output, $parameters, $connection);
        } catch (Exception $e) {
            $this->cannotDropDatabase($output, $parameters, $e);
        }
    }

    /**
     * @param OutputInterface $output
     * @param Connection $connection
     * @param array $parameters
     */
    private function dropDatabase(
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

        $connection->getSchemaManager()->dropDatabase($name);

        $output->writeln(sprintf(
            '<info>Dropped database <comment>%s</comment></info>',
            $name
        ));
    }

    /**
     * @param OutputInterface $output
     * @param array $parameters
     */
    private function doNotDropDatabase(OutputInterface $output, array $parameters)
    {
        $output->writeln(sprintf(
            '<info>Database <comment>%s</comment> doesn\'t exist. Skipped.</info>',
            $this->databaseName($parameters)
        ));
    }

    /**
     * @param OutputInterface $output
     * @param array $parameters
     * @param Exception $e
     */
    protected function cannotDropDatabase(
        OutputInterface $output,
        array $parameters,
        Exception $e
    ) {
        $output->writeln(sprintf(
            '<error>Could not drop database ,<comment>%s</comment></error>',
            $this->databaseName($parameters)
        ));
        $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
    }

    /**
     * @param OutputInterface $output
     * @param array $parameters
     * @param Connection $connection
     */
    private function dropIfExists(
        OutputInterface $output,
        array $parameters,
        Connection $connection
    ) {
        if ($this->databaseExists($parameters, $connection)) {
            $this->dropDatabase($output, $connection, $parameters);
        } else {
            $this->doNotDropDatabase($output, $parameters);
        }
    }
}
