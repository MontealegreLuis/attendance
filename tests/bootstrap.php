<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

$output = [];
exec('php -S localhost:8000 -t tests/fixtures >/dev/null 2>&1 & echo $!', $output);
$pidServer = (int) $output[0];

$output = [];
exec('phantomjs --webdriver=127.0.0.1:8910 >/dev/null 2>&1 & echo $!', $output);
$pidPhantom = (int) $output[0];

register_shutdown_function(function() use ($pidServer, $pidPhantom) {
    exec("kill {$pidServer}");
    exec("kill {$pidPhantom}");
});
