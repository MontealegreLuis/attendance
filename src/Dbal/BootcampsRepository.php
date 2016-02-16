<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\Bootcamps;
use Doctrine\DBAL\Connection;

class BootcampsRepository implements Bootcamps
{
    private $connection;

    /**
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Bootcamp $bootcamp)
    {
        $this->connection->insert('bootcamps', [
            $bootcamp->cohortName(),
        ]);
    }
}
