<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

return [
    'dbal' => [
        'path' => __DIR__ . '/var/attendance.sq3',
        'driver' => 'pdo_sqlite',
    ],
    'dhcp' => [
        'login' => 'http://localhost:8000/Router.html',
        'page' => 'http://localhost:8000/dhcp_status.html',
        'credentials' => [
            'username' => 'admin',
            'password' => 'changeme',
        ],
    ],
    'webdriver' => [
        'host' => '127.0.0.1:4444',
        'capabilities' => [
            WebDriverCapabilityType::BROWSER_NAME => 'phantomjs',
            'phantomjs.page.settings.userAgent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:25.0) Gecko/20100101 Firefox/25.0',
            'phantomjs.page.settings.clearMemoryCaches' => true,
        ],
        'timeout' => 5000,
    ],
    'retry' => [
        'attempts' => 3,
        'interval' => 1000000, // A second
    ],
];
