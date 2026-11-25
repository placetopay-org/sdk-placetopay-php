<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Credit extends Entity
{
    public function __construct(
        protected int $installment,
        protected ?string $code = null,
        protected ?string $type = null,
        protected ?string $groupCode = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            installment: $data['installment'] ?? 1,
            code: $data['code'] ?? null,
            type: $data['type'] ?? null,
            groupCode: $data['groupCode'] ?? null,
        );
    }

    /**
     * Credit charged in a single installment.
     */
    public static function oneInstallment(?string $code = null, ?string $type = null, ?string $groupCode = null): self
    {
        return new self(installment: 1, code: $code, type: $type, groupCode: $groupCode);
    }

    public function code(): ?string
    {
        return $this->code;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function groupCode(): ?string
    {
        return $this->groupCode;
    }

    public function installment(): int
    {
        return $this->installment;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'code' => $this->code(),
            'type' => $this->type(),
            'groupCode' => $this->groupCode(),
            'installment' => $this->installment(),
        ]);
    }
}
