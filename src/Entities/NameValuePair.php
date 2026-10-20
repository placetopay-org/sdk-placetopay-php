<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class NameValuePair extends Entity
{
    protected string $keyword;
    protected mixed $value = null;
    protected string $displayOn = 'none';

    public function __construct(array $data = [])
    {
        $this->load($data, ['keyword', 'value', 'displayOn']);
    }

    public function keyword(): string
    {
        return $this->keyword;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function displayOn(): string
    {
        return $this->displayOn;
    }

    public function toArray(): array
    {
        return [
            'keyword' => $this->keyword(),
            'value' => $this->value(),
            'displayOn' => $this->displayOn(),
        ];
    }
}
