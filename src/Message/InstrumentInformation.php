<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\CreditOption;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class InstrumentInformation extends Entity
{
    use StatusTrait;

    /**
     * @var CreditOption[]
     */
    protected array $credits = [];

    private const REQUIREMENTS = [
        'displayInterest', 'requireOtp', 'requireCvv2', 'requirePockets', 'requireAvs',
        'accountVerification', 'requirePin', 'requireRedirection',
    ];

    /**
     * @param string[] $cardTypes
     * @param array<CreditOption|array> $credits
     * @param array<array{code: string, description: string}> $pockets
     * @param array<array{code: string, description: string}> $bankList
     * @param array<string, bool> $requirements
     */
    public function __construct(
        Status $status,
        protected string $provider = '',
        protected string $serviceCode = '',
        protected string $cardType = '',
        protected array $cardTypes = [],
        protected string $threeDS = '',
        array $credits = [],
        protected array $pockets = [],
        protected array $bankList = [],
        protected string $zipCodeFormat = '',
        protected array $requirements = [],
    ) {
        $this->status = $status;
        $this->credits = array_map(
            fn (CreditOption|array $credit) => $credit instanceof CreditOption ? $credit : CreditOption::fromArray($credit),
            $credits
        );
    }

    public static function fromArray(array $data): self
    {
        $requirements = [];
        foreach (self::REQUIREMENTS as $requirement) {
            $value = $data[$requirement] ?? $data[str_replace('require', 'requiere', $requirement)] ?? null;
            if ($value !== null) {
                $requirements[$requirement] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }

        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            provider: $data['provider'] ?? '',
            serviceCode: $data['serviceCode'] ?? '',
            cardType: $data['cardType'] ?? '',
            cardTypes: $data['cardTypes'] ?? [],
            threeDS: $data['threeDS'] ?? '',
            credits: $data['credits'] ?? [],
            pockets: $data['pockets'] ?? [],
            bankList: $data['bankList'] ?? [],
            zipCodeFormat: $data['zipCodeFormat'] ?? '',
            requirements: $requirements,
        );
    }

    public function provider(): string
    {
        return $this->provider;
    }

    public function serviceCode(): string
    {
        return $this->serviceCode;
    }

    public function cardType(): string
    {
        return $this->cardType;
    }

    /**
     * @return string[]
     */
    public function cardTypes(): array
    {
        return $this->cardTypes;
    }

    public function threeDS(): string
    {
        return $this->threeDS;
    }

    /**
     * @return CreditOption[]
     */
    public function credits(): array
    {
        return $this->credits;
    }

    /**
     * @return array<array{code: string, description: string}>
     */
    public function pockets(): array
    {
        return $this->pockets;
    }

    /**
     * @return array<array{code: string, description: string}>
     */
    public function bankList(): array
    {
        return $this->bankList;
    }

    public function zipCodeFormat(): string
    {
        return $this->zipCodeFormat;
    }

    public function displayInterest(): bool
    {
        return $this->requirements['displayInterest'] ?? false;
    }

    public function requireOtp(): bool
    {
        return $this->requirements['requireOtp'] ?? false;
    }

    public function requireCvv2(): bool
    {
        return $this->requirements['requireCvv2'] ?? false;
    }

    public function requirePockets(): bool
    {
        return $this->requirements['requirePockets'] ?? false;
    }

    public function requireAvs(): bool
    {
        return $this->requirements['requireAvs'] ?? false;
    }

    public function accountVerification(): bool
    {
        return $this->requirements['accountVerification'] ?? false;
    }

    public function requirePin(): bool
    {
        return $this->requirements['requirePin'] ?? false;
    }

    public function requireRedirection(): bool
    {
        return $this->requirements['requireRedirection'] ?? false;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'provider' => $this->provider(),
            'serviceCode' => $this->serviceCode(),
            'cardType' => $this->cardType(),
            'cardTypes' => $this->cardTypes(),
            'threeDS' => $this->threeDS(),
            'credits' => array_map(fn (CreditOption $credit) => $credit->toArray(), $this->credits()),
            'pockets' => $this->pockets(),
            'bankList' => $this->bankList(),
            'zipCodeFormat' => $this->zipCodeFormat(),
            ...$this->requirements,
        ]);
    }
}
