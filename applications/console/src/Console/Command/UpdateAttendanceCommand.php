<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\Attendance\UpdateStudentsCheckout;
use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAttendanceCommand extends AttendanceCommand
{
    /** @var UpdateStudentsCheckout */
    private $useCase;

    /**
     * @param UpdateStudentsCheckout $updateStudentsCheckout
     */
    public function __construct(UpdateStudentsCheckout $updateStudentsCheckout)
    {
        parent::__construct();
        $this->useCase = $updateStudentsCheckout;
    }

    protected function configure()
    {
        $this
            ->setName('codeup:checkout')
            ->setDescription('Poll the DHCP status page to know who is still in class')
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->retryToUpdateAttendance(function () {
            return $this->useCase->updateStudentsCheckout(new DateTime('now'));
        }, $output);
    }
}
