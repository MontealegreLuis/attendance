<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Generate Bootcamp table
 */
class Version20160216171200 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $bootcamps = $schema->createTable('bootcamps');
        $bootcamps->addColumn('bootcamp_id', 'integer', ['unsigned' => true]);
        $bootcamps->addColumn('cohort_name', 'string');
        $bootcamps->addColumn('start_date', 'date');
        $bootcamps->addColumn('stop_date', 'date');
        $bootcamps->addColumn('start_time', 'datetime');
        $bootcamps->addColumn('stop_time', 'datetime');
        $bootcamps->setPrimaryKey(['bootcamp_id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('bootcamps');
    }
}
