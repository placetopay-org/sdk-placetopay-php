<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Instrument extends Entity
{
    public function __construct(
        protected ?Account $bank = null,
        protected ?Token $token = null,
        protected ?Credit $credit = null,
        protected string $pin = '',
        protected string $password = '',
        protected string $pocket = '',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            bank: self::parseEntity($data['bank'] ?? null, Account::class),
            token: self::parseEntity($data['token'] ?? null, Token::class),
            credit: self::parseEntity($data['credit'] ?? null, Credit::class),
            pin: $data['pin'] ?? '',
            password: $data['password'] ?? '',
            pocket: (string)($data['pocket'] ?? ''),
        );
    }

    public function bank(): ?Account
    {
        return $this->bank;
    }

    public function credit(): ?Credit
    {
        return $this->credit;
    }

    public function token(): ?Token
    {
        return $this->token;
    }

    public function pin(): string
    {
        return $this->pin;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function pocket(): string
    {
        return $this->pocket;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'bank' => $this->bank()?->toArray(),
            'credit' => $this->credit()?->toArray(),
            'token' => $this->token()?->toArray(),
            'pin' => $this->pin(),
            'password' => $this->password(),
            'pocket' => $this->pocket(),
        ]);
    }
}
