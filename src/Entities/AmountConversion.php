<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class AmountConversion extends Entity
{
    protected ?AmountBase $from = null;
    protected ?AmountBase $to = null;
    protected float $factor = 1;

    public function __construct(array $data = [])
    {
        if (isset($data['from'])) {
            $this->setFrom($data['from']);
        }

        if (isset($data['to'])) {
            $this->setTo($data['to']);
        }

        if (isset($data['factor'])) {
            $this->setFactor($data['factor']);
        }
    }

    /**
     * Helper function to quickly set all the values.
     */
    public function setAmountBase(AmountBase|array $base): static
    {
        if (is_array($base)) {
            $base = new AmountBase($base);
        }

        $this->setTo($base);
        $this->setFrom($base);
        $this->setFactor(1);
        return $this;
    }

    public function from(): AmountBase
    {
        return $this->from;
    }

    public function to(): AmountBase
    {
        return $this->to;
    }

    public function factor(): float
    {
        return $this->factor;
    }

    public function setFrom(AmountBase|array|null $from): static
    {
        $this->loadEntity($from, 'from', AmountBase::class);
        return $this;
    }

    public function setTo(AmountBase|array|null $to): static
    {
        $this->loadEntity($to, 'to', AmountBase::class);
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
            'from' => $this->from()->toArray(),
            'to' => $this->to()->toArray(),
            'factor' => $this->factor(),
        ];
    }
}
