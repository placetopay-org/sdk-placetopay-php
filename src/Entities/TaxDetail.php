<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class TaxDetail extends Entity
{
    public function __construct(
        protected string $kind = '',
        protected float $amount = 0,
        protected ?float $base = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kind: $data['kind'] ?? '',
            amount: $data['amount'] ?? 0,
            base: $data['base'] ?? null,
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

    public function base(): ?float
    {
        return $this->base;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'kind' => $this->kind(),
            'amount' => $this->amount(),
            'base' => $this->base(),
        ]);
    }
}
