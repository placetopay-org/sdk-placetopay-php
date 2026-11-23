<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class RedirectResponse extends Entity
{
    use StatusTrait;

    public function __construct(
        Status $status,
        protected ?int $requestId = null,
        protected string $processUrl = '',
    ) {
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            requestId: $data['requestId'] ?? null,
            processUrl: $data['processUrl'] ?? '',
        );
    }

    /**
     * Unique transaction code for this request.
     */
    public function requestId(): ?int
    {
        return $this->requestId;
    }

    /**
     * URL to consume when the gateway requires redirection.
     */
    public function processUrl(): string
    {
        return $this->processUrl;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'requestId' => $this->requestId(),
            'processUrl' => $this->processUrl(),
        ]);
    }
}
