<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

use Codeup\Attendance\RegisterStudentsInformation;
use Codeup\Bootcamps\Bootcamps;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class RegisterStudentsController
{
    /** @var Twig */
    private $view;

    /** @var Bootcamps */
    private $bootcamps;

    /** @var RegisterStudentsInformation */
    private $useCase;

    /**
     * @param Twig $view
     * @param Bootcamps $bootcamps
     * @param RegisterStudentsInformation $registerStudentsInformation
     */
    public function __construct(
        Twig $view,
        Bootcamps $bootcamps,
        RegisterStudentsInformation $registerStudentsInformation
    ) {
        $this->bootcamps = $bootcamps;
        $this->view = $view;
        $this->useCase = $registerStudentsInformation;
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

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     */
    public function saveStudentsInformation(Request $request, Response $response)
    {
        /** @var \Slim\Http\UploadedFile $file */
        $file = $request->getUploadedFiles()['students'];
        $path = __DIR__ . "/../../../var/bootcamps/{$file->getClientFilename()}";
        $file->moveTo($path);

        $this->useCase->register($path, $request->getParam('bootcamp_id'));

        return $response->withRedirect('/');
    }
}
