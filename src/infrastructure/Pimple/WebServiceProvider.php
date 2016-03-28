<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Pimple\Container;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class WebServiceProvider extends AttendanceServiceProvider
{
    public function register(Container $container)
    {
        parent::register($container);
        $container['view'] = function () use ($container) {
            $view = new Twig($this->options['twig']['templates'], [
                'cache' => $this->options['twig']['cache'],
                'debug' => $this->options['twig']['debug'],
            ]);
            $view->addExtension(new TwigExtension(
                $container['router'],
                $container['request']->getUri()
            ));

            return $view;
        };
    }
}