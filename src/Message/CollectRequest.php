<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Entities\Instrument;
use PlacetoPay\PlacetoPaySdk\Exceptions\PlacetoPayException;

class CollectRequest extends RedirectRequest
{
    /**
     * @param mixed ...$request named arguments accepted by RedirectRequest::__construct, payer is required
     * @throws PlacetoPayException
     */
    public function __construct(
        protected Instrument $instrument,
        protected ?string $provider = null,
        mixed ...$request,
    ) {
        parent::__construct(...$request);

        if (!$this->payer) {
            throw PlacetoPayException::forDataNotProvided('The payer is required to collect');
        }
    }

    /**
     * @throws PlacetoPayException
     */
    public static function fromArray(array $data): self
    {
        return new self(...[
            ...self::attributesFromArray($data),
            'instrument' => self::parseEntity($data['instrument'] ?? null, Instrument::class) ?? new Instrument(),
            'provider' => $data['provider'] ?? null,
        ]);
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
            'instrument' => $this->instrument()->toArray(),
            'provider' => $this->provider(),
        ]);
    }
}
