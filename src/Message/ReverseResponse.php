<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Entities\Transaction;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class ReverseResponse extends Entity
{
    use StatusTrait;

    public function __construct(
        Status $status,
        protected ?Transaction $payment = null,
    ) {
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            payment: self::parseEntity($data['payment'] ?? null, Transaction::class),
        );
    }

    public function payment(): ?Transaction
    {
        return $this->payment;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'payment' => $this->payment()?->toArray(),
        ]);
    }
}
