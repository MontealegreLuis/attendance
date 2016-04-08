<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\Students;
use DateTime;
use Doctrine\DBAL\Connection;
use League\Csv\Reader;
use PDO;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class RegisterStudentsController
{
    /** @var Twig */
    private $view;

    /** @var Bootcamps */
    private $bootcamps;

    /** @var Students */
    private $students;

    /** @var Connection */
    private $connection;

    /**
     * @param Twig $view
     * @param Bootcamps $bootcamps
     * @param Students $students
     * @param Connection $connection
     */
    public function __construct(
        Twig $view,
        Bootcamps $bootcamps,
        Students $students,
        Connection $connection
    ) {
        $this->bootcamps = $bootcamps;
        $this->view = $view;
        $this->connection = $connection;
        $this->students = $students;
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

        $csv = Reader::createFromPath($path);
        $statement = $this->connection->prepare(
            "INSERT INTO students (student_id, name, mac_address, bootcamp_id)
             VALUES (:student_id, :name, :mac_address, :bootcamp_id)"
        );

        $bootcampId = $request->getParam('bootcamp_id');

        $csv->each(function ($student) use (&$statement, $bootcampId) {
            $statement->bindValue(
                ':student_id',
                $this->students->nextStudentId()->value(),
                PDO::PARAM_STR
            );
            $statement->bindValue(':name', $student[0], PDO::PARAM_STR);
            $statement->bindValue(':mac_address', $student[1], PDO::PARAM_STR);
            $statement->bindValue(':bootcamp_id', $bootcampId, PDO::PARAM_STR);

            return $statement->execute();
        });

        return $response->withRedirect('/');
    }
}
