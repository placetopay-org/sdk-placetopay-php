<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Entities\Token;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class TokenInformation extends Entity
{
    use StatusTrait;

    public function __construct(
        Status $status,
        protected ?int $id = null,
        protected string $type = '',
        protected ?Token $token = null,
    ) {
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        $information = is_array($data['data'] ?? null) ? $data['data'] : [];

        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            id: $information['id'] ?? null,
            type: $information['type'] ?? '',
            token: self::parseEntity($information, Token::class),
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function token(): ?Token
    {
        return $this->token;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'data' => $this->token() ? $this->arrayFilter([
                'id' => $this->id(),
                'type' => $this->type(),
                ...$this->token()->toArray(),
            ]) : null,
        ]);
    }
}
