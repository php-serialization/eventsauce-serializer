<?php

declare(strict_types=1);

namespace PhpSerialization\EventsauceSerializer\Cache;

use EventSauce\ObjectHydrator\ObjectMapper;

interface DefinitionCache
{
    /**
     * @param class-string $class
     *
     * @throws \Throwable
     */
    public function createMapper(string $class): ObjectMapper;
    public function reset(): void;
}
