<?php

namespace PlacetoPay\PlacetoPaySdk\Contracts;

use PlacetoPay\PlacetoPaySdk\Helpers\ArrayHelper;
use PlacetoPay\PlacetoPaySdk\Traits\LoaderTrait;

abstract class Entity
{
    use LoaderTrait;

    /**
     * Extracts the information for the entity.
     */
    abstract public function toArray(): array;

    /**
     * @param class-string<Entity> $class
     */
    protected function loadEntity(mixed $data, string $attribute, string $class): static
    {
        if ($data) {
            if (is_array($data)) {
                $data = new $class($data);
            }

            $this->{$attribute} = $data instanceof $class ? $data : null;
        }

        return $this;
    }

    protected function arrayFilter(array $array): array
    {
        return ArrayHelper::filter($array);
    }
}
