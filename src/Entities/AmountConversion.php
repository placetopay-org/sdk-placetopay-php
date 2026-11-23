<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class AmountConversion extends Entity
{
    public function __construct(
        protected ?AmountBase $from = null,
        protected ?AmountBase $to = null,
        protected float $factor = 1,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            from: self::parseEntity($data['from'] ?? null, AmountBase::class),
            to: self::parseEntity($data['to'] ?? null, AmountBase::class),
            factor: $data['factor'] ?? 1,
        );
    }

    /**
     * Helper function to quickly set all the values.
     */
    public function setAmountBase(AmountBase|array $base): static
    {
        if (is_array($base)) {
            $base = AmountBase::fromArray($base);
        }

        $this->setTo($base);
        $this->setFrom($base);
        $this->setFactor(1);
        return $this;
    }

    public function from(): ?AmountBase
    {
        return $this->from;
    }

    public function to(): ?AmountBase
    {
        return $this->to;
    }

    public function factor(): float
    {
        return $this->factor;
    }

    public function setFrom(AmountBase|array|null $from): static
    {
        $this->from = self::parseEntity($from, AmountBase::class);
        return $this;
    }

    public function setTo(AmountBase|array|null $to): static
    {
        $this->to = self::parseEntity($to, AmountBase::class);
        return $this;
    }

    public function setFactor(float $factor): static
    {
        $this->factor = $factor;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'from' => $this->from()?->toArray(),
            'to' => $this->to()?->toArray(),
            'factor' => $this->factor(),
        ];
    }
}
