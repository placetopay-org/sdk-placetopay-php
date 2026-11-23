<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class CancelSessionResponse extends Entity
{
    use StatusTrait;

    public function __construct(
        Status $status,
        protected ?RedirectInformation $session = null,
    ) {
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            session: self::parseEntity($data['session'] ?? null, RedirectInformation::class),
        );
    }

    public function session(): ?RedirectInformation
    {
        return $this->session;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'session' => $this->session()?->toArray(),
        ]);
    }
}
