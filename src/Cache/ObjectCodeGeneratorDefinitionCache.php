<?php

declare(strict_types=1);

namespace PhpSerialization\EventsauceSerializer\Cache;

use EventSauce\ObjectHydrator\ObjectMapper;
use EventSauce\ObjectHydrator\ObjectMapperCodeGenerator;

final class ObjectCodeGeneratorDefinitionCache implements DefinitionCache
{
    private const OPTIMIZED_SERIALIZER_SUFFIX = 'EventsauceOptimizedMapper';

    /**
     * @var array<class-string, ObjectMapper>
     */
    private static array $mappers = [];

    /**
     * @param non-empty-string $namespace
     * @param non-empty-string $cacheDir
     */
    public function __construct(
        private string $namespace,
        private string $cacheDir,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function createMapper(string $class): ObjectMapper
    {
        if (isset(self::$mappers[$class])) {
            return self::$mappers[$class];
        }

        \set_error_handler(static function (int $errorNo, string $errorStr): bool {
            if (0 === error_reporting()) {
                return false;
            }

            throw new \RuntimeException($errorStr, $errorNo);
        });

        try {
            /** @var class-string<ObjectMapper> */
            $objectFqcn = $this->namespace.'\\'.$class.self::OPTIMIZED_SERIALIZER_SUFFIX;

            $filename = $this->resolveFileName($objectFqcn);

            $dumper = new ObjectMapperCodeGenerator();

            if (false === file_exists($filename)) {
                file_put_contents($filename, $dumper->dump([$class], $objectFqcn));
            }

            /** @psalm-suppress UnresolvableInclude */
            require_once $filename;

            return self::$mappers[$class] = new $objectFqcn();
        } finally {
            \restore_error_handler();
        }
    }

    public function reset(): void
    {
        self::$mappers = [];

        $iterator = new \RecursiveDirectoryIterator($this->cacheDir);
        $iterator = new \RecursiveIteratorIterator($iterator);
        $iterator = new \RegexIterator($iterator, '/'.self::OPTIMIZED_SERIALIZER_SUFFIX.'/');

        /** @var string $name */
        foreach ($iterator as $name => $_file) {
            @unlink($name);
        }
    }

    /**
     * @param class-string $class
     *
     * @return string
     */
    private function resolveFileName(string $class): string
    {
        $class = str_replace('\\', '', $class);

        return rtrim($this->cacheDir, '/')."/$class.php";
    }
}
