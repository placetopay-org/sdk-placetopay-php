<?php

namespace PlacetoPay\PlacetoPaySdk\Traits;

trait LoaderTrait
{
    /**
     * @param string[] $keys
     */
    public function load(mixed $data, array $keys): void
    {
        if (!is_array($data)) {
            return;
        }

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $this->$key = $data[$key];
            }
        }
    }
}
