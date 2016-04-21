<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Nelmio\Alice\Fixtures\Loader;
use Nelmio\Alice\PersisterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedDatabaseCommand extends Command
{
    /** @var PersisterInterface */
    private $persister;

    /** @var Loader */
    private $loader;

    /**
     * @param PersisterInterface $persister
     * @param Loader $loader
     */
    public function __construct(PersisterInterface $persister, Loader $loader)
    {
        parent::__construct();
        $this->persister = $persister;
        $this->loader = $loader;
    }

    protected function configure()
    {
        $this
            ->setName('codeup:db:seed')
            ->setDescription('Seed the database with fake information.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $objects = $this->loader->load(
            __DIR__ . '/../../../../var/fixtures/attendance.yml'
        );
        $this->persister->persist($objects);
        $output->writeln(
            '<info>Fake database information has been loaded successfully.</info>'
        );
    }
}
