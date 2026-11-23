<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Traits\FieldsTrait;

class Subscription extends Entity
{
    use FieldsTrait;

    /**
     * @param array<NameValuePair|array> $fields
     */
    public function __construct(
        protected string $reference = '',
        protected string $description = '',
        array $fields = [],
    ) {
        $this->setFields($fields);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            reference: $data['reference'] ?? '',
            description: $data['description'] ?? '',
            fields: $data['fields'] ?? [],
        );
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'reference' => $this->reference(),
            'description' => $this->description(),
            'fields' => $this->fieldsToArray(),
        ]);
    }
}
