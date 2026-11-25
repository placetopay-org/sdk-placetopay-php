<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class CreditOption extends Entity
{
    /**
     * @var int[]
     */
    protected array $installments = [];

    /**
     * @param array<int|string> $installments
     */
    public function __construct(
        protected string $code = '',
        protected string $type = '',
        protected string $groupCode = '',
        protected string $description = '',
        array $installments = [],
    ) {
        $this->installments = array_map('intval', $installments);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'] ?? '',
            type: $data['type'] ?? '',
            groupCode: $data['groupCode'] ?? '',
            description: $data['description'] ?? '',
            installments: $data['installments'] ?? [],
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

    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return int[]
     */
    public function installments(): array
    {
        return $this->installments;
    }

    public function toCredit(int $installment): Credit
    {
        return new Credit(
            installment: $installment,
            code: $this->code() !== '' ? $this->code() : null,
            type: $this->type() !== '' ? $this->type() : null,
            groupCode: $this->groupCode() !== '' ? $this->groupCode() : null,
        );
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'code' => $this->code(),
            'type' => $this->type(),
            'groupCode' => $this->groupCode(),
            'description' => $this->description(),
            'installments' => $this->installments(),
        ]);
    }
}
