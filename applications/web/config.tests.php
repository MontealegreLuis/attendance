<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'dbal' => [
        'path' => __DIR__ . '/var/attendance.sq3',
        'driver' => 'pdo_sqlite',
    ],
    'twig' => [
        'templates' => __DIR__ . '/src/infrastructure/Twig/Resources/templates',
        'cache' => __DIR__ . '/var/cache',
        'debug' => true
    ],
];
