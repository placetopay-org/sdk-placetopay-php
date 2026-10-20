<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

class DispersionPayment extends Payment
{
    /**
     * @var Payment[]
     */
    protected array $dispersion = [];

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (isset($data['dispersion'])) {
            $this->setDispersion($data['dispersion']);
        }
    }

    /**
     * @return Payment[]
     */
    public function dispersion(): array
    {
        return $this->dispersion;
    }

    public function setDispersion(array $data): self
    {
        foreach ($data as $payment) {
            $entity = new Payment($payment);
            $entity->setReference($this->reference())
                ->setDescription($this->description());
            $this->dispersion[] = $entity;
        }
        return $this;
    }

    protected function dispersionToArray(): array
    {
        return array_map(fn (Payment $payment) => $payment->toArray(), $this->dispersion);
    }

    public function toArray(): array
    {
        return self::arrayFilter(array_replace(parent::toArray(), [
            'dispersion' => $this->dispersionToArray(),
        ]));
    }
}
