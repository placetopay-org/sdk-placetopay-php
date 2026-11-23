<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Entities\SubscriptionInformation;
use PlacetoPay\PlacetoPaySdk\Entities\Transaction;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class RedirectInformation extends Entity
{
    use StatusTrait;

    /**
     * @var Transaction[]
     */
    protected array $payment = [];

    /**
     * @param array<Transaction|array> $payment
     */
    public function __construct(
        Status $status,
        protected ?int $requestId = null,
        protected ?RedirectRequest $request = null,
        array $payment = [],
        protected ?SubscriptionInformation $subscription = null,
    ) {
        $this->status = $status;
        $this->setPayment($payment);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            requestId: $data['requestId'] ?? null,
            request: self::parseEntity($data['request'] ?? null, RedirectRequest::class),
            payment: $data['payment'] ?? [],
            subscription: self::parseEntity($data['subscription'] ?? null, SubscriptionInformation::class),
        );
    }

    public function requestId(): ?int
    {
        return $this->requestId;
    }

    public function request(): ?RedirectRequest
    {
        return $this->request;
    }

    /**
     * @return Transaction[]
     */
    public function payment(): array
    {
        return $this->payment;
    }

    public function subscription(): ?SubscriptionInformation
    {
        return $this->subscription;
    }

    public function setPayment(mixed $payments): self
    {
        if ($payments) {
            $this->payment = [];

            $payments = ($payments['transaction'] ?? null) ?: $payments;
            foreach ($payments as $payment) {
                $this->payment[] = $payment instanceof Transaction ? $payment : Transaction::fromArray($payment);
            }
        }
        return $this;
    }

    public function setSubscription(SubscriptionInformation|array|null $subscription): self
    {
        $this->subscription = self::parseEntity($subscription, SubscriptionInformation::class);
        return $this;
    }

    private function paymentToArray(): array
    {
        return array_map(fn (Transaction $payment) => $payment->toArray(), $this->payment());
    }

    public function lastApprovedTransaction(): ?Transaction
    {
        return $this->lastTransaction(true);
    }

    /**
     * Obtains the last transaction made to the session.
     */
    public function lastTransaction(bool $approved = false): ?Transaction
    {
        foreach ($this->payment() as $transaction) {
            if (!$approved || $transaction->isApproved()) {
                return $transaction;
            }
        }

        return null;
    }

    /**
     * Returns the last authorization associated with the session.
     */
    public function lastAuthorization(): string
    {
        return $this->lastApprovedTransaction()?->authorization() ?? '';
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'requestId' => $this->requestId(),
            'status' => $this->status()->toArray(),
            'request' => $this->request()?->toArray(),
            'payment' => $this->paymentToArray(),
            'subscription' => $this->subscription()?->toArray(),
        ]);
    }
}
