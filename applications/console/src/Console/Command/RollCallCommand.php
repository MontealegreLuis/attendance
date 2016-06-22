<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Attendance\DoRollCall;
use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Scrape the router's DHCP page to know who are the students currently in class
 */
class RollCallCommand extends AttendanceCommand
{
    /** @var DoRollCall */
    private $useCase;

    /**
     * @param DoRollCall $rollCall
     */
    public function __construct(DoRollCall $rollCall)
    {
        parent::__construct();
        $this->useCase = $rollCall;
    }

    protected function configure()
    {
        $this
            ->setName('codeup:rollcall')
            ->setDescription('Poll the DHCP status page to know who is currently in class')
            ->setHelp(<<<HELP
If you run this command with the option <info>--locally</info> it will connect to PHP's built-in server 
and it will serve the files in the <info>var/fixtures</info> folder instead.
HELP
           )
            ->addOption(
                'locally',
                'l',
                InputOption::VALUE_NONE,
                'Connect to PHP built-in server instead of the router\'s DHCP page'
            )
        ;
    }

    /**
     * Retry a configured amount of times if connection cannot be established
     * immediately. Show the information of the present students, if found.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->retryToUpdateAttendance(function () {
            return $this->useCase->rollCall(new DateTime('now'));
        }, $output);
    }
}
