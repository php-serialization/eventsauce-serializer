<?php

declare(strict_types=1);

namespace PhpSerialization\EventsauceSerializer\Tests;

use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use PhpSerialization\EventsauceSerializer\EventsauceSerializer;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class EventsauceSerializerTest extends TestCase
{
    public function testObjectWasSerialized(): void
    {
        $serializer = new EventsauceSerializer(new ObjectMapperUsingReflection());

        self::assertEquals(['name' => 'Test'], $serializer->serialize(new A(name: 'Test')));
    }

    public function testObjectWasUnserialized(): void
    {
        $serializer = new EventsauceSerializer(new ObjectMapperUsingReflection());

        $a = $serializer->unserialize(A::class, ['name' => 'Test']);

        self::assertInstanceOf(A::class, $a);
        self::assertEquals('Test', $a->name);
    }
}
