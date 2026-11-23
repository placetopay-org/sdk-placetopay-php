<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Discount extends Entity
{
    public function __construct(
        protected string $code = '',
        protected string $type = '',
        protected float $amount = 0,
        protected float $base = 0,
        protected ?float $percent = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'] ?? '',
            type: $data['type'] ?? '',
            amount: $data['amount'] ?? 0,
            base: $data['base'] ?? 0,
            percent: $data['percent'] ?? null,
        );
    }

    public function code(): string
    {
        return $this->code;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function base(): float
    {
        return $this->base;
    }

    public function percent(): ?float
    {
        return $this->percent;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'code' => $this->code(),
            'type' => $this->type(),
            'amount' => $this->amount(),
            'base' => $this->base(),
            'percent' => $this->percent(),
        ]);
    }
}
