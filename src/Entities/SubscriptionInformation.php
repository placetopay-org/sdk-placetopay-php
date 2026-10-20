<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class SubscriptionInformation extends Entity
{
    use StatusTrait;

    /**
     * The type of this subscription could be token or account for the time being.
     */
    protected string $type;
    /**
     * @var NameValuePair[]
     */
    protected array $instrument = [];

    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->loadEntity($data['status'], 'status', Status::class);

        if (isset($data['instrument'])) {
            $this->setInstrument($data['instrument']);
        }
    }

    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return NameValuePair[]
     */
    public function instrument(): array
    {
        return $this->instrument;
    }

    public function setInstrument(array $instrumentData): self
    {
        $this->instrument = [];
        foreach ($instrumentData['item'] ?? $instrumentData as $nvp) {
            if (is_array($nvp)) {
                $nvp = new NameValuePair($nvp);
            }

            if ($nvp instanceof NameValuePair) {
                $this->instrument[] = $nvp;
            }
        }
        return $this;
    }

    public function instrumentToArray(): array
    {
        return array_map(fn (NameValuePair $field) => $field->toArray(), $this->instrument());
    }

    /**
     * Parses the instrument as the proper entity, Keep in mind that can be null
     * if no instrument its provided.
     */
    public function parseInstrument(): Account|Token|null
    {
        $instrumentNVP = $this->instrument();
        if (!$instrumentNVP) {
            return null;
        }

        $data = [
            'status' => $this->status(),
        ];
        foreach ($instrumentNVP as $nvp) {
            $data[$nvp->keyword()] = $nvp->value();
        }

        return match ($this->type()) {
            'token' => new Token($data),
            'account' => new Account($data),
            default => null,
        };
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type(),
            'status' => $this->status()->toArray(),
            'instrument' => $this->instrumentToArray(),
        ]);
    }
}
