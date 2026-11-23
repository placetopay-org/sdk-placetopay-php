<?php

namespace PlacetoPay\PlacetoPaySdk\Message;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Entities\DispersionPayment;
use PlacetoPay\PlacetoPaySdk\Entities\NameValuePair;
use PlacetoPay\PlacetoPaySdk\Entities\Person;
use PlacetoPay\PlacetoPaySdk\Entities\Subscription;
use PlacetoPay\PlacetoPaySdk\Traits\FieldsTrait;

class RedirectRequest extends Entity
{
    use FieldsTrait;

    protected string $expiration;

    /**
     * @param string|null $expiration defaults to one day from now
     * @param array<NameValuePair|array> $fields
     */
    public function __construct(
        protected string $returnUrl = '',
        protected string $ipAddress = '',
        protected string $userAgent = '',
        protected ?Person $payer = null,
        protected ?Person $buyer = null,
        protected ?DispersionPayment $payment = null,
        protected ?Subscription $subscription = null,
        protected string $locale = 'es_CO',
        protected string $paymentMethod = '',
        protected string $cancelUrl = '',
        ?string $expiration = null,
        protected bool $captureAddress = false,
        protected bool $skipResult = false,
        protected bool $noBuyerFill = false,
        array $fields = [],
        protected array $metadata = [],
        protected string $type = '',
    ) {
        $this->expiration = $expiration ?? date('c', strtotime('+1 day'));
        $this->setFields($fields);
    }

    public static function fromArray(array $data): self
    {
        return new self(...self::attributesFromArray($data));
    }

    /**
     * Maps the service representation of a request to the constructor arguments.
     */
    protected static function attributesFromArray(array $data): array
    {
        return [
            'returnUrl' => $data['returnUrl'] ?? '',
            'ipAddress' => $data['ipAddress'] ?? '',
            'userAgent' => $data['userAgent'] ?? '',
            'payer' => self::parseEntity($data['payer'] ?? null, Person::class),
            'buyer' => self::parseEntity($data['buyer'] ?? null, Person::class),
            'payment' => self::parseEntity($data['payment'] ?? null, DispersionPayment::class),
            'subscription' => self::parseEntity($data['subscription'] ?? null, Subscription::class),
            'locale' => $data['locale'] ?? 'es_CO',
            'paymentMethod' => $data['paymentMethod'] ?? '',
            'cancelUrl' => $data['cancelUrl'] ?? '',
            'expiration' => $data['expiration'] ?? null,
            'captureAddress' => $data['captureAddress'] ?? false,
            'skipResult' => $data['skipResult'] ?? false,
            'noBuyerFill' => $data['noBuyerFill'] ?? false,
            'fields' => $data['fields'] ?? [],
            'metadata' => $data['metadata'] ?? [],
            'type' => $data['type'] ?? '',
        ];
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function language(): string
    {
        return strtoupper(substr($this->locale(), 0, 2));
    }

    public function payer(): ?Person
    {
        return $this->payer;
    }

    public function buyer(): ?Person
    {
        return $this->buyer;
    }

    public function payment(): ?DispersionPayment
    {
        return $this->payment;
    }

    public function subscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function cancelUrl(): string
    {
        return $this->cancelUrl;
    }

    public function returnUrl(): string
    {
        return $this->returnUrl;
    }

    public function ipAddress(): string
    {
        return $this->ipAddress;
    }

    public function userAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * A redirect request itself doesnt have a reference, but it should
     * know how to get it.
     */
    public function reference(): string
    {
        return $this->payment()?->reference() ?? $this->subscription()->reference();
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function setReturnUrl(string $returnUrl): self
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function setCancelUrl(string $cancelUrl): self
    {
        $this->cancelUrl = $cancelUrl;
        return $this;
    }

    public function setExpiration(string $expiration): self
    {
        $this->expiration = $expiration;
        return $this;
    }

    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * Returns the expiration datetime for this request.
     */
    public function expiration(): string
    {
        return $this->expiration;
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function captureAddress(): bool
    {
        return $this->captureAddress;
    }

    public function skipResult(): bool
    {
        return filter_var($this->skipResult, FILTER_VALIDATE_BOOLEAN);
    }

    public function noBuyerFill(): bool
    {
        return filter_var($this->noBuyerFill, FILTER_VALIDATE_BOOLEAN);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'locale' => $this->locale(),
            'payer' => $this->payer()?->toArray(),
            'buyer' => $this->buyer()?->toArray(),
            'payment' => $this->payment()?->toArray(),
            'subscription' => $this->subscription()?->toArray(),
            'fields' => $this->fieldsToArray(),
            'returnUrl' => $this->returnUrl(),
            'paymentMethod' => $this->paymentMethod(),
            'cancelUrl' => $this->cancelUrl(),
            'ipAddress' => $this->ipAddress(),
            'userAgent' => $this->userAgent(),
            'expiration' => $this->expiration(),
            'captureAddress' => $this->captureAddress(),
            'skipResult' => $this->skipResult(),
            'noBuyerFill' => $this->noBuyerFill(),
            'type' => $this->type(),
            'metadata' => $this->metadata(),
        ]);
    }
}
