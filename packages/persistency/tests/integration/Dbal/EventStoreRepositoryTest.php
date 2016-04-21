<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\ContractTests\EventStore;
use Codeup\ContractTests\EventStoreTest;
use Codeup\JmsSerializer\JsonEventSerializer;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\TestHelpers\SetupDatabaseConnection;

class EventStoreRepositoryTest extends EventStoreTest
{
    use SetupDatabaseConnection;

    function storeInstance()
    {
        $store = new EventStoreRepository(
            $connection =$this->connection(),
            new JsonEventSerializer(new JsonSerializer())
        );
        $connection->executeQuery('DELETE FROM events');
        $connection->executeQuery('UPDATE events_seq SET next_val = 0');

        return $store;
    }
}
