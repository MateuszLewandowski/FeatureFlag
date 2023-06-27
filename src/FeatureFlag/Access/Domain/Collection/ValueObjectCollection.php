<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Collection;

use Shared\ValueObject;

abstract class ValueObjectCollection
{
    protected function __construct(string $type, protected ?array $collection)
    {
        if (null === $this->collection) {
            $this->collection = [];
        }

        foreach ($this->collection as &$item) {
            $item = new $type($item);
        }
    }

    public function toArray(): array
    {
        return array_map(static function ($item) {
            return $item->value;
        }, $this->collection);
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function exists(mixed $value): bool
    {
        return 0 < count(
                array_filter($this->collection, function (ValueObject $item) use ($value) {
                    return $item->value === $value;
                })
            );
    }
}
