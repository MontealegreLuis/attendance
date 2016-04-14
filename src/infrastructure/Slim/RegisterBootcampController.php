<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

use Codeup\Attendance\RegisterBootcamp;
use Codeup\Attendance\RegisterBootcampInformation;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class RegisterBootcampController
{
    /** @var Twig */
    private $view;

    /** @var RegisterBootcamp */
    private $useCase;

    /**
     * @param Twig $view
     * @param RegisterBootcamp $registerBootcamp
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
        $path = $this->moveUploadedFile($request);

        $this->useCase->register(
            RegisterBootcampInformation::from($request->getParsedBody()),
            $path
        );

        return $response->withRedirect('/students');
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function moveUploadedFile(Request $request)
    {
        /** @var \Slim\Http\UploadedFile $file */
        $file = $request->getUploadedFiles()['students'];
        $path = __DIR__ . "/../../../var/bootcamps/{$file->getClientFilename()}";
        $file->moveTo($path);

        return $path;
    }
}
