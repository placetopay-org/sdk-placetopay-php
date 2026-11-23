<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Token extends Entity
{
    /**
     * @param string $cvv Just in case the token will be utilized
     */
    public function __construct(
        protected string $token = '',
        protected string $subtoken = '',
        protected string $franchise = '',
        protected string $franchiseName = '',
        protected string $issuerName = '',
        protected string $lastDigits = '',
        protected string $validUntil = '',
        protected string $cvv = '',
        protected int $installments = 0,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            token: $data['token'] ?? '',
            subtoken: $data['subtoken'] ?? '',
            franchise: $data['franchise'] ?? '',
            franchiseName: $data['franchiseName'] ?? '',
            issuerName: $data['issuerName'] ?? '',
            lastDigits: $data['lastDigits'] ?? '',
            validUntil: $data['validUntil'] ?? '',
            cvv: $data['cvv'] ?? '',
            installments: $data['installments'] ?? 0,
        );
    }

    public function token(): string
    {
        return $this->token;
    }

    public function subtoken(): string
    {
        return $this->subtoken;
    }

    public function franchise(): string
    {
        return $this->franchise;
    }

    public function franchiseName(): string
    {
        return $this->franchiseName;
    }

    public function issuerName(): string
    {
        return $this->issuerName;
    }

    public function lastDigits(): string
    {
        return $this->lastDigits;
    }

    public function validUntil(): string
    {
        return $this->validUntil;
    }

    public function cvv(): string
    {
        return $this->cvv;
    }

    public function installments(): string
    {
        return (string)$this->installments;
    }

    public function expiration(): string
    {
        return date('m/y', strtotime($this->validUntil()));
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'token' => $this->token(),
            'subtoken' => $this->subtoken(),
            'franchise' => $this->franchise(),
            'franchiseName' => $this->franchiseName(),
            'issuerName' => $this->issuerName(),
            'lastDigits' => $this->lastDigits(),
            'validUntil' => $this->validUntil(),
            'cvv' => $this->cvv(),
            'installments' => $this->installments(),
        ]);
    }
}
