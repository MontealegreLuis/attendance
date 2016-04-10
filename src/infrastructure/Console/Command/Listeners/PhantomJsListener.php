<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command\Listeners;

class PhantomJsListener
{
    /** @var int */
    private $pidPhantomJs;

    public function startPhantomJs()
    {
        $output = [];
        exec(
            'phantomjs --webdriver=127.0.0.1:8910 >/dev/null 2>&1 & echo $!',
            $output
        );
        $this->pidPhantomJs = (int) $output[0];
    }

    public function stopPhantomJs()
    {
        exec("kill {$this->pidPhantomJs}");
    }
}
