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
 * Generate tables for Bootcamp and Student entities.
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

        $students = $schema->createTable('students');
        $students->addColumn('student_id', 'integer', ['unsigned' => true]);
        $students->addColumn('name', 'string');
        $students->addColumn('mac_address', 'string');
        $students->addColumn('bootcamp_id', 'integer', ['unsigned' => true]);
        $students->setPrimaryKey(['student_id']);
        $students->addForeignKeyConstraint(
            $bootcamps,
            ['bootcamp_id'], // Local column
            ['bootcamp_id']  // Foreign column
        );

        $attendance = $schema->createTable('attendances');
        $attendance->addColumn('attendance_id', 'integer', ['unsigned' => true]);
        $attendance->addColumn('date', 'datetime');
        $attendance->addColumn('type', 'boolean');
        $attendance->addColumn('student_id', 'integer', ['unsigned' => true]);
        $attendance->setPrimaryKey(['attendance_id']);
        $attendance->addForeignKeyConstraint(
            $students,
            ['student_id'], // Local column
            ['student_id']  // Foreign column
        );

        $attendanceSequence = $schema->createTable('attendances_seq');
        $attendanceSequence->addColumn('next_val', 'integer', [
            'unsigned' => true
        ]);

        $events = $schema->createTable('events');
        $events->addColumn('event_id', 'integer', ['unsigned' => true]);
        $events->addColumn('body', 'string');
        $events->addColumn('type', 'string');
        $events->addColumn('occurred_on', 'datetime');
        $events->setPrimaryKey(['event_id']);

        $eventsSequence = $schema->createTable('events_seq');
        $eventsSequence->addColumn('next_val', 'integer', [
            'unsigned' => true
        ]);

        $messages = $schema->createTable('published_messages');
        $messages->addColumn('message_id', 'integer', ['unsigned' => true]);
        $messages->addColumn('most_recent_message_id', 'integer', ['unsigned' => true]);

        $messagesSequence = $schema->createTable('messages_seq');
        $messagesSequence->addColumn('next_val', 'integer', [
            'unsigned' => true
        ]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('bootcamps');
        $schema->dropTable('students');
        $schema->dropTable('attendances');
        $schema->dropTable('attendances_seq');
        $schema->dropTable('events');
        $schema->dropTable('events_seq');
        $schema->dropTable('published_messages');
        $schema->dropTable('messages_seq');
    }
}
