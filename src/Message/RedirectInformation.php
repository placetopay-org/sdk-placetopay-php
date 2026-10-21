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

    protected ?int $requestId;
    protected ?RedirectRequest $request = null;
    /**
     * @var Transaction[]
     */
    protected array $payment = [];
    protected ?SubscriptionInformation $subscription = null;

    public function __construct(array $data = [])
    {
        $this->requestId = $data['requestId'] ?? null;
        $this->loadEntity($data['status'], 'status', Status::class);
        $this->loadEntity($data['request'] ?? null, 'request', RedirectRequest::class);

        if (isset($data['payment'])) {
            $this->setPayment($data['payment']);
        }

        if (isset($data['subscription'])) {
            $this->setSubscription($data['subscription']);
        }
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
                $this->payment[] = new Transaction($payment);
            }
        }
        return $this;
    }

    public function setSubscription(SubscriptionInformation|array|null $subscription): self
    {
        $this->loadEntity($subscription, 'subscription', SubscriptionInformation::class);
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
