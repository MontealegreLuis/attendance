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

class RegisterStudentsController
{
    /** @var Twig */
    private $view;

    /** @var Bootcamps */
    private $bootcamps;

    /**
     * @param Twig $view
     * @param Bootcamps $bootcamps
     */
    public function __construct(Twig $view, Bootcamps $bootcamps)
    {
        $this->bootcamps = $bootcamps;
        $this->view = $view;
    }

    /**
     * @param $_
     * @param Response $response
     * @return Response
     */
    public function showStudentsForm($_, Response $response)
    {
        return $response->write($this->view->fetch('students.html.twig', [
            'bootcamps' => $this->bootcamps->notYetFinished(new DateTime('now')),
        ]));
    }
}
