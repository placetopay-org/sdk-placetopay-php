<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

class DispersionPayment extends Payment
{
    /**
     * @var Payment[]
     */
    protected array $dispersion = [];

    public static function fromArray(array $data): self
    {
        $payment = new self(...self::attributesFromArray($data));

        return $payment->setDispersion($data['dispersion'] ?? []);
    }

    /**
     * @return Payment[]
     */
    public function dispersion(): array
    {
        return $this->dispersion;
    }

    /**
     * @param array<Payment|array> $data
     */
    public function setDispersion(array $data): self
    {
        foreach ($data as $payment) {
            $entity = $payment instanceof Payment ? $payment : Payment::fromArray($payment);
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
