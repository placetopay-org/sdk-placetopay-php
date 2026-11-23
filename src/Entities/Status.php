<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Enums\StatusType;

class Status extends Entity
{
    public function __construct(
        protected string $status = 'ERROR',
        protected string $reason = '',
        protected string $message = '',
        protected string $date = '',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'] ?? StatusType::ERROR->value,
            reason: $data['reason'] ?? '',
            message: $data['message'] ?? '',
            date: $data['date'] ?? '',
        );
    }

    public function status(): string
    {
        return $this->status;
    }

    public function type(): StatusType
    {
        return StatusType::fromName($this->status());
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function date(): string
    {
        return $this->date;
    }

    public function isSuccessful(): bool
    {
        return $this->type() === StatusType::OK;
    }

    public function isApproved(): bool
    {
        return $this->type() === StatusType::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->type() === StatusType::REJECTED;
    }

    public function isError(): bool
    {
        return $this->type() === StatusType::ERROR;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status(),
            'reason' => $this->reason(),
            'message' => $this->message(),
            'date' => $this->date(),
        ];
    }

    public static function quick(StatusType|string $status, string $reason, string $message = '', string $date = ''): self
    {
        return new self(
            status: $status instanceof StatusType ? $status->value : $status,
            reason: $reason,
            message: $message,
            date: $date ?: date('c'),
        );
    }
}
