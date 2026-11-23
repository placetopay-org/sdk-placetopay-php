<?php

namespace PlacetoPay\PlacetoPaySdk\Contracts;

use PlacetoPay\PlacetoPaySdk\Helpers\ArrayHelper;

abstract class Entity
{
    /**
     * Extracts the information for the entity.
     */
    abstract public function toArray(): array;

    /**
     * Resolves a nested entity from an instance or from its array representation.
     *
     * @template T of Entity
     * @param class-string<T> $class
     * @return T|null
     */
    protected static function parseEntity(mixed $data, string $class): ?self
    {
        if ($data instanceof $class) {
            return $data;
        }

        return is_array($data) && $data ? $class::fromArray($data) : null;
    }

    protected function arrayFilter(array $array): array
    {
        return ArrayHelper::filter($array);
    }
}
