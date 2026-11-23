<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Recurring extends Entity
{
    /**
     * @param string $periodicity Frequency to resubmit the transaction. Y = annual  M = monthly  D = daily.
     * @param int|null $maxPeriods Depends on the number of times that it makes the charge, corresponds to maximum
     *                             times that the recurrence will happen. If you do not want to set up should
     *                             indicated -1. You must specify this parameter or dueDate.
     */
    public function __construct(
        protected string $periodicity = '',
        protected int $interval = 0,
        protected string $nextPayment = '',
        protected ?int $maxPeriods = null,
        protected string $dueDate = '',
        protected string $notificationUrl = '',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            periodicity: $data['periodicity'] ?? '',
            interval: $data['interval'] ?? 0,
            nextPayment: $data['nextPayment'] ?? '',
            maxPeriods: $data['maxPeriods'] ?? null,
            dueDate: $data['dueDate'] ?? '',
            notificationUrl: $data['notificationUrl'] ?? '',
        );
    }

    public function periodicity(): string
    {
        return $this->periodicity;
    }

    public function interval(): int
    {
        return $this->interval;
    }

    public function nextPayment(): string
    {
        return $this->nextPayment;
    }

    public function maxPeriods(): ?int
    {
        return $this->maxPeriods;
    }

    public function dueDate(): string
    {
        return $this->dueDate;
    }

    public function notificationUrl(): string
    {
        return $this->notificationUrl;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'periodicity' => $this->periodicity(),
            'interval' => $this->interval(),
            'nextPayment' => $this->nextPayment(),
            'maxPeriods' => $this->maxPeriods(),
            'dueDate' => $this->dueDate(),
            'notificationUrl' => $this->notificationUrl(),
        ]);
    }
}
