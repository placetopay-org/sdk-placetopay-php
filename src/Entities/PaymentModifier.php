<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class PaymentModifier extends Entity
{
    public const TYPE_FEDERAL_GOVERNMENT = 'FEDERAL_GOVERNMENT';

    public function __construct(
        protected ?string $type = null,
        protected ?string $code = null,
        protected array $additional = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'] ?? null,
            code: $data['code'] ?? null,
            additional: $data['additional'] ?? [],
        );
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function code(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function additional(?string $key = null, mixed $default = null): mixed
    {
        if ($key) {
            return $this->additional[$key] ?? $default;
        }

        return $this->additional;
    }

    public function setAdditional(array $additional): self
    {
        $this->additional = $additional;

        return $this;
    }

    public function mergeAdditional(array $data): self
    {
        $this->additional = array_replace($this->additional, $data);

        return $this;
    }

    public function toArray(): array
    {
        return self::arrayFilter([
            'type' => $this->type(),
            'code' => $this->code(),
            'additional' => $this->additional(),
        ]);
    }
}
