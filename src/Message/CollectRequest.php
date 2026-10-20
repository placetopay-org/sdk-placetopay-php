<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Entities\Instrument;
use PlacetoPay\PlacetoPaySdk\Entities\Person;
use PlacetoPay\PlacetoPaySdk\Exceptions\PlacetoPayException;

class CollectRequest extends RedirectRequest
{
    protected Instrument $instrument;

    protected string $returnUrl = '';
    protected string $ipAddress = '';
    protected string $userAgent = '';
    protected ?string $provider = null;

    /**
     * @throws PlacetoPayException
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (!$this->payer) {
            throw PlacetoPayException::forDataNotProvided('The payer is required to collect');
        }

        $this->loadEntity($data['instrument'], 'instrument', Instrument::class);
        $this->provider = $data['provider'] ?? null;
    }

    public function payer(): Person
    {
        return $this->payer;
    }

    public function provider(): ?string
    {
        return $this->provider;
    }

    public function instrument(): Instrument
    {
        return $this->instrument;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'instrument' => $this->instrument()?->toArray(),
            'provider' => $this->provider(),
        ]);
    }
}
