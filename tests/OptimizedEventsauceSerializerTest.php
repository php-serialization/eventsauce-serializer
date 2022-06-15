<?php

declare(strict_types=1);

namespace PhpSerialization\EventsauceSerializer\Tests;

use PhpSerialization\EventsauceSerializer\Cache\DefinitionCache;
use PhpSerialization\EventsauceSerializer\Cache\ObjectCodeGeneratorDefinitionCache;
use PhpSerialization\EventsauceSerializer\EventsauceSerializerFactory;
use PhpSerializer\Serializer\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class OptimizedEventsauceSerializerTest extends TestCase
{
    private DefinitionCache $definitionCache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->definitionCache = new ObjectCodeGeneratorDefinitionCache('App', __DIR__.'/cache');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->definitionCache->reset();
    }

    public function testObjectSerializedUsingOptimizedSerializer(): void
    {
        $serializer = $this->createOptimizedEventsauceSerializer();

        self::assertEquals(['name' => 'Test'], $serializer->serialize(new A('Test')));
        self::assertFileExists(__DIR__.'/cache/AppPhpSerializationEventsauceSerializerTestsAEventsauceOptimizedMapper.php');
    }

    public function testObjectUnserializedUsingOptimizedSerializer(): void
    {
        $serializer = $this->createOptimizedEventsauceSerializer();

        $a = $serializer->unserialize(A::class, ['name' => 'Test']);
        self::assertInstanceOf(A::class, $a);
        self::assertEquals('Test', $a->name);
        self::assertFileExists(__DIR__.'/cache/AppPhpSerializationEventsauceSerializerTestsAEventsauceOptimizedMapper.php');
    }

    private function createOptimizedEventsauceSerializer(): Serializer
    {
        return (new EventsauceSerializerFactory())
            ->createOptimizedEventsauceSerializer(definitionCache: $this->definitionCache);
    }
}
