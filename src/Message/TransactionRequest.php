<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\AmountBase;
use PlacetoPay\PlacetoPaySdk\Enums\TransactionAction;
use PlacetoPay\PlacetoPaySdk\Exceptions\PlacetoPayException;

class TransactionRequest extends Entity
{
    public function __construct(
        protected int $internalReference,
        protected TransactionAction $action,
        protected ?AmountBase $amount = null,
    ) {
    }

    /**
     * @throws PlacetoPayException
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['internalReference'], $data['action'])) {
            throw PlacetoPayException::forDataNotProvided('The internalReference and the action are required');
        }

        return new self(
            internalReference: $data['internalReference'],
            action: $data['action'] instanceof TransactionAction
                ? $data['action']
                : TransactionAction::tryFrom($data['action']) ?? throw PlacetoPayException::forDataNotProvided('Unknown transaction action ' . $data['action']),
            amount: self::parseEntity($data['amount'] ?? null, AmountBase::class),
        );
    }

    public function internalReference(): int
    {
        return $this->internalReference;
    }

    public function action(): TransactionAction
    {
        return $this->action;
    }

    public function amount(): ?AmountBase
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'internalReference' => $this->internalReference(),
            'action' => $this->action()->value,
            'amount' => $this->amount()?->toArray(),
        ]);
    }
}
