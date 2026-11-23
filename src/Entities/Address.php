<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Address extends Entity
{
    public function __construct(
        protected string $street = '',
        protected string $city = '',
        protected string $state = '',
        protected string $postalCode = '',
        protected string $country = '',
        protected string $phone = '',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            street: $data['street'] ?? '',
            city: $data['city'] ?? '',
            state: $data['state'] ?? '',
            postalCode: $data['postalCode'] ?? '',
            country: $data['country'] ?? '',
            phone: $data['phone'] ?? '',
        );
    }

    public function street(): string
    {
        return $this->street;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'street' => $this->street(),
            'city' => $this->city(),
            'state' => $this->state(),
            'postalCode' => $this->postalCode(),
            'country' => $this->country(),
            'phone' => $this->phone(),
        ]);
    }
}
