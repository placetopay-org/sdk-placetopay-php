<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class NameValuePair extends Entity
{
    public function __construct(
        protected string $keyword = '',
        protected mixed $value = null,
        protected string $displayOn = 'none',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            keyword: $data['keyword'] ?? '',
            value: $data['value'] ?? null,
            displayOn: $data['displayOn'] ?? 'none',
        );
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
