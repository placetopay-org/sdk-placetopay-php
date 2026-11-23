<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class AmountDetail extends Entity
{
    public function __construct(
        protected string $kind = '',
        protected float $amount = 0,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kind: $data['kind'] ?? '',
            amount: $data['amount'] ?? 0,
        );
    }

    public function kind(): string
    {
        return $this->kind;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'kind' => $this->kind(),
            'amount' => $this->amount(),
        ]);
    }
}
