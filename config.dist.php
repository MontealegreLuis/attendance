<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'dbal' => [
        'path' => 'var/attendance.sq3',
        'driver' => 'pdo_sqlite',
    ],
    'dhcp' => [
        'page' => 'http://localhost:8000/dhcp_status.html',
    ],
];
