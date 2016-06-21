<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

return [
    'dbal' => [
        'dbname' => getenv('MYSQL_DB'),
        'user' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'host' => getenv('MYSQL_HOST'),
        'driver' => 'pdo_mysql',
    ],
    'dhcp' => [
        'login' => getenv('DHCP_LOGIN_PAGE'),
        'page' => getenv('DHCP_STATUS_PAGE'),
        'credentials' => [
            'username' => getenv('DHCP_USER'),
            'password' => getenv('DHCP_PASSWORD'),
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
