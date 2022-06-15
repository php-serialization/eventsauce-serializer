<?php

declare(strict_types=1);

use PhpSerialization\EventsauceSerializer\EventsauceSerializerFactory;

require_once __DIR__.'/../vendor/autoload.php';

final class SomeCommand
{
    public function __construct(public string $name)
    {
    }
}

$serializer = (new EventsauceSerializerFactory())->createOptimizedEventsauceSerializer('App', __DIR__);

var_dump($serializer->serialize(new SomeCommand(name: 'Test')));
var_dump($serializer->unserialize(SomeCommand::class, ['name' => 'Test']));