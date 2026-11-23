<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class Notification extends Entity
{
    use StatusTrait;

    public function __construct(
        Status $status,
        private readonly string $tranKey,
        protected int $requestId = 0,
        protected string $reference = '',
        protected string $signature = '',
    ) {
        $this->status = $status;
    }

    public static function fromArray(array $data, string $tranKey): self
    {
        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            tranKey: $tranKey,
            requestId: $data['requestId'] ?? 0,
            reference: $data['reference'] ?? '',
            signature: $data['signature'] ?? '',
        );
    }

    public function requestId(): int
    {
        return $this->requestId;
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function signature(): string
    {
        return $this->signature;
    }

    public function makeSignature(): string
    {
        return sha1($this->requestId() . $this->status()->status() . $this->status()->date() . $this->tranKey);
    }

    public function isValidNotification(): bool
    {
        return hash_equals($this->makeSignature(), $this->signature());
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status()->toArray(),
            'requestId' => $this->requestId(),
            'reference' => $this->reference(),
            'signature' => $this->signature(),
        ];
    }
}
