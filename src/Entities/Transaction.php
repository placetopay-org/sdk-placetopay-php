<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Traits\StatusTrait;

class Transaction extends Entity
{
    use StatusTrait;

    /**
     * @var NameValuePair[]
     */
    protected array $processorFields = [];

    /**
     * @param string $reference Reference as the commerce provides.
     * @param int|null $internalReference Reference for PlacetoPay.
     * @param array<NameValuePair|array> $processorFields
     */
    public function __construct(
        Status $status,
        protected string $reference = '',
        protected ?int $internalReference = null,
        protected string $paymentMethod = '',
        protected string $paymentMethodName = '',
        protected string $issuerName = '',
        protected ?Discount $discount = null,
        protected ?AmountConversion $amount = null,
        protected string $authorization = '',
        protected string $receipt = '',
        protected string $franchise = '',
        protected bool $refunded = false,
        array $processorFields = [],
    ) {
        $this->status = $status;
        $this->setProcessorFields($processorFields);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: self::parseEntity($data['status'] ?? null, Status::class) ?? new Status(),
            reference: $data['reference'] ?? '',
            internalReference: $data['internalReference'] ?? null,
            paymentMethod: $data['paymentMethod'] ?? '',
            paymentMethodName: $data['paymentMethodName'] ?? '',
            issuerName: $data['issuerName'] ?? '',
            discount: self::parseEntity($data['discount'] ?? null, Discount::class),
            amount: self::parseEntity($data['amount'] ?? null, AmountConversion::class),
            authorization: $data['authorization'] ?? '',
            receipt: $data['receipt'] ?? '',
            franchise: $data['franchise'] ?? '',
            refunded: $data['refunded'] ?? false,
            processorFields: $data['processorFields'] ?? [],
        );
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function internalReference(): ?int
    {
        return $this->internalReference;
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function paymentMethodName(): string
    {
        return $this->paymentMethodName;
    }

    public function issuerName(): string
    {
        return $this->issuerName;
    }

    public function amount(): ?AmountConversion
    {
        return $this->amount;
    }

    public function authorization(): string
    {
        return $this->authorization;
    }

    public function receipt(): string
    {
        return $this->receipt;
    }

    public function franchise(): string
    {
        return $this->franchise;
    }

    /**
     * @return NameValuePair[]
     */
    public function processorFields(): array
    {
        return $this->processorFields;
    }

    public function refunded(): bool
    {
        return $this->refunded;
    }

    public function discount(): ?Discount
    {
        return $this->discount;
    }

    /**
     * Determines if the transaction information its valid, meaning the query was
     * successful not the transaction.
     */
    public function isSuccessful(): bool
    {
        return !$this->status()->isError();
    }

    /**
     * Determines if the transaction has been approved.
     */
    public function isApproved(): bool
    {
        return $this->status()->isApproved();
    }

    public function setProcessorFields(mixed $data): self
    {
        $data = $data['item'] ?? $data;

        if (is_array($data)) {
            foreach ($data as $nvp) {
                $this->processorFields[] = $nvp instanceof NameValuePair ? $nvp : NameValuePair::fromArray($nvp);
            }
        }

        return $this;
    }

    public function processorFieldsToArray(): array
    {
        return array_map(fn (NameValuePair $field) => $field->toArray(), $this->processorFields());
    }

    /**
     * Parses the processorFields as a key value array.
     */
    public function additionalData(): array
    {
        $data = [];
        foreach ($this->processorFields() as $field) {
            $data[$field->keyword()] = $field->value();
        }

        return $data;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'internalReference' => $this->internalReference(),
            'paymentMethod' => $this->paymentMethod(),
            'paymentMethodName' => $this->paymentMethodName(),
            'issuerName' => $this->issuerName(),
            'amount' => $this->amount()?->toArray(),
            'authorization' => $this->authorization(),
            'reference' => $this->reference(),
            'receipt' => $this->receipt(),
            'franchise' => $this->franchise(),
            'refunded' => $this->refunded(),
            'discount' => $this->discount()?->toArray(),
            'processorFields' => $this->processorFieldsToArray(),
        ]);
    }
}
