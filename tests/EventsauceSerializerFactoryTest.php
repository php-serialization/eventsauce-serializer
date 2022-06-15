<?php

declare(strict_types=1);

namespace PhpSerialization\EventsauceSerializer\Tests;

use PhpSerialization\EventsauceSerializer\EventsauceSerializer;
use PhpSerialization\EventsauceSerializer\EventsauceSerializerFactory;
use PhpSerialization\EventsauceSerializer\OptimizedEventsauceSerializer;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class EventsauceSerializerFactoryTest extends TestCase
{
    public function testCreateEventsauceSerializer(): void
    {
        $factory = new EventsauceSerializerFactory();

        self::assertInstanceOf(EventsauceSerializer::class, $factory->createEventsauceSerializer());
    }

    public function testCreateOptimizedEventsauceSerializer(): void
    {
        $factory = new EventsauceSerializerFactory();

        self::assertInstanceOf(OptimizedEventsauceSerializer::class, $factory->createOptimizedEventsauceSerializer('App', __DIR__));
    }

    public function testSerializerWasNotCreatedDueToTheInvalidConfiguration(): void
    {
        $factory = new EventsauceSerializerFactory();

        self::expectException(\InvalidArgumentException::class);
        $factory->createOptimizedEventsauceSerializer();
    }
}
