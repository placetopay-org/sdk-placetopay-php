<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Instrument;
use PlacetoPay\PlacetoPaySdk\Exceptions\PlacetoPayException;
use PlacetoPay\PlacetoPaySdk\Helpers\ArrayHelper;

class InstrumentRequest extends Entity
{
    /**
     * @throws PlacetoPayException
     */
    public function __construct(
        protected Instrument $instrument,
        protected string $locale = '',
    ) {
        $token = $this->instrument->token();
        if (!$token?->token() && !$token?->subtoken()) {
            throw PlacetoPayException::forDataNotProvided('The instrument token or subtoken is required');
        }
    }

    /**
     * @throws PlacetoPayException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            instrument: self::parseEntity($data['instrument'] ?? null, Instrument::class) ?? new Instrument(),
            locale: $data['locale'] ?? '',
        );
    }

    public function instrument(): Instrument
    {
        return $this->instrument;
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'instrument' => ['token' => ArrayHelper::only($this->instrument()->token()->toArray(), ['token', 'subtoken'])],
            'locale' => $this->locale(),
        ]);
    }
}
