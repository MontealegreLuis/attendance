<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

use Slim\Http\Response;
use Slim\Views\Twig;

class RegisterBootcampController
{
    /** @var View */
    private $view;

    /**
     * @param Twig $view
     */
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    /**
     * @param $_
     * @param Response $response
     * @return Response
     */
    public function showRegistrationForm($_, Response $response)
    {
        return $response->write($this->view->fetch('bootcamp.html.twig'));
    }
}
