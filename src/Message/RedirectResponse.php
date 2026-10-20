<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class RedirectResponse extends Entity
{
    use StatusTrait;

    protected ?int $requestId = null;
    protected string $processUrl = '';

    public function __construct(array $data = [])
    {
        $this->load($data, ['requestId', 'processUrl']);
        $this->loadEntity($data['status'], 'status', Status::class);
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
