<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\ContractTests;

use Codeup\DataBuilders\A;
use Codeup\DomainEvents\EventStore;
use PHPUnit_Framework_TestCase as TestCase;

abstract class EventStoreTest extends TestCase
{
    /** @var EventStore */
    private $store;

    /** @var StoredEvent */
    private $event2;

    /** @var StoredEvent */
    private $event4;

    /** @before */
    function generateFixtures()
    {
        $this->store = $this->storeInstance();
        $this->store->append(A::studentHasCheckedIn()->build());
        $this->event2 = $this->store->append(A::studentHasCheckedIn()->build());
        $this->store->append(A::studentHasCheckedIn()->build());
        $this->event4 = $this->store->append(A::studentHasCheckedIn()->build());
    }

    /** @test */
    function it_should_retrieve_all_stored_events()
    {
        $this->assertCount(4, $this->store->allEvents());
    }

    /** @test */
    function it_should_retrieve_2_out_of_4_events_if_second_event_id_is_provided()
    {
        $this->assertCount(
            2,
            $this->store->eventsStoredAfter($this->event2->id())
        );
    }

    /** @test */
    function it_should_retrieve_0_events_if_last_event_id_is_provided()
    {
        $this->assertCount(
            0,
            $this->store->eventsStoredAfter($this->event4->id())
        );
    }

    /**
     * @return EventStore
     */
    abstract function storeInstance();
}
