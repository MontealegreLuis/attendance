<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

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

    /** @var Bootcamps */
    private $bootcamps;

    /**
     * @param Twig $view
     * @param Bootcamps $bootcamps
     */
    public function __construct(Twig $view, Bootcamps $bootcamps)
    {
        $this->view = $view;
        $this->bootcamps = $bootcamps;
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
        $information = $request->getParsedBody();
        $bootcamp = Bootcamp::start(
            $this->bootcamps->nextBootcampId(),
            Duration::between(
                DateTime::createFromFormat('Y-m-d', $information['start_date']),
                DateTime::createFromFormat('Y-m-d', $information['stop_date'])
            ),
            $information['cohort_name'],
            Schedule::withClassTimeBetween(
                DateTime::createFromFormat('H:i', $information['start_time']),
                DateTime::createFromFormat('H:i', $information['stop_time'])
            )
        );
        $this->bootcamps->add($bootcamp);

        return $response->withRedirect('/');
    }
}
