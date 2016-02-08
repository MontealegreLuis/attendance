<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

$output = [];
exec('php -S localhost:8000 -t tests/fixtures >/dev/null 2>&1 & echo $!', $output);
$pid = (int) $output[0];

register_shutdown_function(function() use ($pid) {
    exec('kill ' . $pid);
});
