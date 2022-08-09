<?php

declare(strict_types=1);

namespace PhpSerialization\EventsauceSerializer;

use EventSauce\ObjectHydrator\DefinitionProvider;
use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use PhpSerialization\EventsauceSerializer\Cache\DefinitionCache;
use PhpSerialization\EventsauceSerializer\Cache\ObjectCodeGeneratorDefinitionCache;
use PhpSerializer\Serializer\Serializer;

final class EventsauceSerializerFactory
{
    public function createEventsauceSerializer(?DefinitionProvider $definitionProvider = null): Serializer
    {
        return new EventsauceSerializer(new ObjectMapperUsingReflection($definitionProvider));
    }

    /**
     * @param non-empty-string|null $namespace
     * @param non-empty-string|null $cacheDir
     *
     * @throws \InvalidArgumentException
     */
    public function createOptimizedEventsauceSerializer(
        ?string $namespace = null,
        ?string $cacheDir = null,
        ?DefinitionCache $definitionCache = null,
    ): Serializer {
        if (null === $definitionCache) {
            if (null === $namespace || $cacheDir === null) {
                throw new \InvalidArgumentException('You should provide DefinitionCache or namespace and cacheDir for default DefinitionCache implementation.');
            }

            $definitionCache = new ObjectCodeGeneratorDefinitionCache($namespace, $cacheDir);
        }

        return new OptimizedEventsauceSerializer($definitionCache);
    }
}
