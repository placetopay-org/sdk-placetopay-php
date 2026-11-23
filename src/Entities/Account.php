<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class Account extends Entity
{
    use StatusTrait;

    public function __construct(
        protected string $bankCode = '',
        protected string $bankName = '',
        protected string $accountType = '',
        protected string $accountNumber = '',
        ?Status $status = null,
    ) {
        if ($status) {
            $this->status = $status;
        }
    }

    public static function fromArray(array $data): self
    {
        return new self(
            bankCode: $data['bankCode'] ?? '',
            bankName: $data['bankName'] ?? '',
            accountType: $data['accountType'] ?? '',
            accountNumber: $data['accountNumber'] ?? '',
            status: self::parseEntity($data['status'] ?? null, Status::class),
        );
    }

    public function bankCode(): string
    {
        return $this->bankCode;
    }

    public function bankName(): string
    {
        return $this->bankName;
    }

    public function accountType(): string
    {
        return $this->accountType;
    }

    public function accountNumber(): string
    {
        return $this->accountNumber;
    }

    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status()->toArray(),
            'bankCode' => $this->bankCode(),
            'bankName' => $this->bankName(),
            'accountType' => $this->accountType(),
            'accountNumber' => $this->accountNumber(),
        ]);
    }

    public function type(): string
    {
        return 'account';
    }

    /**
     * Last digits for the instrument subscribed in order to display to the
     * user.
     */
    public function lastDigits(): string
    {
        return substr($this->accountNumber(), -4);
    }
}
