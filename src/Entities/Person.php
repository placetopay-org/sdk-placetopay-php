<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Enums\DocumentType;

class Person extends Entity
{
    public function __construct(
        protected string $document = '',
        protected string $documentType = '',
        protected string $name = '',
        protected string $surname = '',
        protected string $company = '',
        protected string $email = '',
        protected string $mobile = '',
        protected ?Address $address = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            document: $data['document'] ?? '',
            documentType: $data['documentType'] ?? '',
            name: $data['name'] ?? '',
            surname: $data['surname'] ?? '',
            company: $data['company'] ?? '',
            email: $data['email'] ?? '',
            mobile: $data['mobile'] ?? '',
            address: self::parseEntity($data['address'] ?? null, Address::class),
        );
    }

    public function document(): string
    {
        return $this->document;
    }

    public function documentType(): string
    {
        return $this->documentType;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function surname(): string
    {
        return $this->surname;
    }

    public function company(): string
    {
        return $this->company;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function address(): ?Address
    {
        return $this->address;
    }

    public function mobile(): string
    {
        return $this->mobile;
    }

    public function isBusiness(): bool
    {
        return DocumentType::tryFrom($this->documentType())?->isBusiness() ?? false;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'document' => $this->document(),
            'documentType' => $this->documentType(),
            'name' => $this->name(),
            'surname' => $this->surname(),
            'email' => $this->email(),
            'mobile' => $this->mobile(),
            'company' => $this->company(),
            'address' => $this->address()?->toArray(),
        ]);
    }
}
