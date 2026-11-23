<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class AmountBase extends Entity
{
    public function __construct(
        protected string $currency = 'COP',
        protected float $total = 0,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            currency: $data['currency'] ?? 'COP',
            total: $data['total'] ?? 0,
        );
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function total(): float
    {
        return $this->total;
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency(),
            'total' => $this->total(),
        ];
    }
}
