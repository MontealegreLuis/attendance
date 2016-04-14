<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

use Codeup\Attendance\RegisterBootcamp;
use Codeup\Attendance\RegisterBootcampInformation;
use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\Schedule;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class RegisterBootcampController
{
    /** @var View */
    private $view;

    /** @var RegisterBootcamp */
    private $useCase;

    /**
     * @param Twig $view
     * @param Bootcamps $bootcamps
     */
    public function __construct(Twig $view, RegisterBootcamp $registerBootcamp)
    {
        $this->view = $view;
        $this->useCase = $registerBootcamp;
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

    public function registerBootcamp(Request $request, Response $response)
    {
        $input = $request->getParsedBody();
        $this->useCase->register(RegisterBootcampInformation::from($input));

        return $response->withRedirect('/students');
    }
}
