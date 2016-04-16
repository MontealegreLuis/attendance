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
echo "PHP server is running with PID $pidServer", PHP_EOL;

$output = [];
exec('phantomjs --webdriver=127.0.0.1:8910 >/dev/null 2>&1 & echo $!', $output);
$pidPhantom = (int) $output[0];
echo "PhantomJS is running with PID $pidPhantom", PHP_EOL;

sleep(3); // Wait a moment for PhantomJS

register_shutdown_function(function() use ($pidServer, $pidPhantom) {
    exec("kill {$pidServer}");
    echo "Stopped PHP server with PID $pidServer", PHP_EOL;
    exec("kill {$pidPhantom}");
    echo "Stopped PhantomJS with PID $pidPhantom", PHP_EOL;
});
