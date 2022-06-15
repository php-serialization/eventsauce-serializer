<?php

declare(strict_types=1);

namespace PhpSerialization\EventsauceSerializer;

use EventSauce\ObjectHydrator\ObjectMapper;
use PhpSerializer\Serializer\Serializer;
use PhpSerializer\Serializer\UnableToSerializeObject;
use PhpSerializer\Serializer\UnableToUnserializeObject;

final class EventsauceSerializer implements Serializer
{
    public function __construct(private ObjectMapper $eventsauce)
    {
    }

    /**
     * @param object $object
     * @param array<string, mixed> $context
     *
     * @return mixed
     *
     * @throws UnableToSerializeObject
     */
    public function serialize(object $object, array $context = []): mixed
    {
        try {
            /** @var array */
            return $this->eventsauce->serializeObject($object);
        } catch (\Throwable $e) {
            throw new UnableToSerializeObject($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     * @param array $payload
     * @param array<string, mixed> $context
     *
     * @return T
     *
     * @throws UnableToUnserializeObject
     */
    public function unserialize(string $class, array $payload, array $context = []): object
    {
        try {
            /** @var T */
            return $this->eventsauce->hydrateObject($class, $payload);
        } catch (\Throwable $e) {
            throw new UnableToUnserializeObject($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
