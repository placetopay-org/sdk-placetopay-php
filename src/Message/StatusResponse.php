<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class StatusResponse extends Entity
{
    use StatusTrait;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
        );
    }

    public function toArray(): array
    {
        return ['status' => $this->status()->toArray()];
    }
}
