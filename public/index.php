<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Codeup\Pimple\WebServiceProvider;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);
(new WebServiceProvider(require __DIR__ . '/../config.php'))
    ->register($container = $app->getContainer())
;

$app->get('/', 'HomeController:summaryAction');
$app->get('/bootcamps', 'RegisterBootcampController:showRegistrationForm');
$app->run();
