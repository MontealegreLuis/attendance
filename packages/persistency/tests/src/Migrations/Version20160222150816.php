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
 * Generate initial values for 'sequence' tables
 */
class Version20160222150816 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->connection->insert('bootcamps_seq', [
            'next_val' => 0,
        ]);
        $this->connection->insert('students_seq', [
            'next_val' => 0,
        ]);
        $this->connection->insert('attendances_seq', [
            'next_val' => 0,
        ]);
        $this->connection->insert('events_seq', [
            'next_val' => 0,
        ]);
        $this->connection->insert('messages_seq', [
            'next_val' => 0,
        ]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->connection->executeQuery('DELETE FROM bootcamps_seq');
        $this->connection->executeQuery('DELETE FROM students_seq');
        $this->connection->executeQuery('DELETE FROM attendances_seq');
        $this->connection->executeQuery('DELETE FROM events_seq');
        $this->connection->executeQuery('DELETE FROM messages_seq');
    }
}
