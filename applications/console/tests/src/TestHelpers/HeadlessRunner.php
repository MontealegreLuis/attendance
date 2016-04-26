<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\TestHelpers;

class HeadlessRunner
{
    /** @var int */
    private $phantomJsPid;

    /** @var int */
    private $phpServerPid;

    public function startPhantomJs()
    {
        $output = [];
        exec(
            'phantomjs --webdriver=127.0.0.1:4444 >/dev/null 2>&1 & echo $!',
            $output
        );
        $this->phantomJsPid = (int) $output[0];

        sleep(2);
    }

    public function stopPhantomJs()
    {
        exec("kill {$this->phantomJsPid}");
    }

    public function startPhpServer()
    {
        $output = [];
        exec(
            sprintf(
                'php -S localhost:8000 -t %s >/dev/null 2>&1 & echo $!',
                __DIR__ . '/../../fixtures'
            ),
            $output
        );
        $this->phpServerPid = (int) $output[0];
    }

    public function stopPhpServer()
    {
        exec("kill {$this->phpServerPid}");
    }

    /**
     * @return int
     */
    public function phantomJsPid()
    {
        return $this->phantomJsPid;
    }

    /**
     * @return int
     */
    public function phpServerPid()
    {
        return $this->phpServerPid;
    }
}
