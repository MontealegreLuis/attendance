<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

use Codeup\Bootcamps\Bootcamps;
use DateTime;
use Slim\Http\Response;
use Slim\Views\Twig;

class HomeController
{
    /** @var Bootcamps */
    private $bootcamps;

    /** @var Twig */
    private $view;

    /**
     * @param Bootcamps $bootcamps
     */
    public function __construct(Bootcamps $bootcamps, Twig $view)
    {
        $this->bootcamps = $bootcamps;
        $this->view = $view;
    }

    /**
     * @param $_ Request
     * @param Response $response
     * @return Response
     */
    public function summaryAction($_, Response $response)
    {
        //TODO: this should be really today, get timestamp from $_SERVER ?
        $today = new DateTime('2016-03-06');

        return $response->write($this->view->fetch('attendance.html.twig', [
            'todayAttendance' => $this->bootcamps->attendance($today),
            'attendanceOnTime' => $this->bootcamps->onTime($today),
            'daysWithPerfectAttendance' => $this->bootcamps->daysWithPerfectAttendance(),
        ]));
    }
}
