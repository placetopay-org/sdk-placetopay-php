<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Credit extends Entity
{
    public function __construct(
        protected string $code = '',
        protected string $type = '',
        protected string $groupCode = '',
        protected int $installment = 0,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'] ?? '',
            type: $data['type'] ?? '',
            groupCode: $data['groupCode'] ?? '',
            installment: $data['installment'] ?? 0,
        );
    }

    public function code(): string
    {
        return $this->code;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function groupCode(): string
    {
        return $this->groupCode;
    }

    public function installment(): int
    {
        return $this->installment;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code(),
            'type' => $this->type(),
            'groupCode' => $this->groupCode(),
            'installment' => $this->installment(),
        ];
    }
}
