<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Traits\FieldsTrait;

class Payment extends Entity
{
    use FieldsTrait;

    /**
     * @var Item[]
     */
    protected array $items = [];

    /** @var PaymentModifier[] */
    protected array $modifiers = [];

    /**
     * @param array<Item|array> $items
     * @param array<NameValuePair|array> $fields
     * @param array<PaymentModifier|array> $modifiers
     */
    public function __construct(
        protected string $reference = '',
        protected string $description = '',
        protected ?Amount $amount = null,
        protected bool $allowPartial = false,
        protected ?Person $shipping = null,
        array $items = [],
        protected ?Recurring $recurring = null,
        protected ?Discount $discount = null,
        protected ?Instrument $instrument = null,
        public bool $subscribe = false,
        protected ?int $agreement = null,
        protected string $agreementType = '',
        array $fields = [],
        array $modifiers = [],
    ) {
        $this->setItems($items);
        $this->setFields($fields);
        $this->setModifiers($modifiers);
    }

    public static function fromArray(array $data): self
    {
        return new self(...self::attributesFromArray($data));
    }

    /**
     * Maps the service representation of a payment to the constructor arguments.
     */
    protected static function attributesFromArray(array $data): array
    {
        return [
            'reference' => $data['reference'] ?? '',
            'description' => $data['description'] ?? '',
            'amount' => self::parseEntity($data['amount'] ?? null, Amount::class),
            'allowPartial' => $data['allowPartial'] ?? false,
            'shipping' => self::parseEntity($data['shipping'] ?? null, Person::class),
            'items' => $data['items'] ?? [],
            'recurring' => self::parseEntity($data['recurring'] ?? null, Recurring::class),
            'discount' => self::parseEntity($data['discount'] ?? null, Discount::class),
            'subscribe' => $data['subscribe'] ?? false,
            'agreement' => $data['agreement'] ?? null,
            'agreementType' => $data['agreementType'] ?? '',
            'fields' => $data['fields'] ?? [],
            'modifiers' => is_array($data['modifiers'] ?? null) ? $data['modifiers'] : [],
        ];
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function amount(): ?Amount
    {
        return $this->amount;
    }

    public function agreement(): ?int
    {
        return $this->agreement;
    }

    public function agreementType(): string
    {
        return $this->agreementType;
    }

    public function allowPartial(): bool
    {
        return filter_var($this->allowPartial, FILTER_VALIDATE_BOOLEAN);
    }

    public function shipping(): ?Person
    {
        return $this->shipping;
    }

    /**
     * @return Item[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function recurring(): ?Recurring
    {
        return $this->recurring;
    }

    public function subscribe(): bool
    {
        return $this->subscribe;
    }

    public function discount(): ?Discount
    {
        return $this->discount;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setItems(array $items): self
    {
        $this->items = array_map(
            fn (Item|array $data) => is_array($data) ? Item::fromArray($data) : $data,
            $items['item'] ?? $items
        );

        return $this;
    }

    public function itemsToArray(): array
    {
        return array_map(fn (Item $item) => $item->toArray(), $this->items());
    }

    /**
     * @return PaymentModifier[]
     */
    public function modifiers(): array
    {
        return $this->modifiers;
    }

    public function setModifiers(array $modifiers): self
    {
        $this->modifiers = [];

        foreach ($modifiers as $modifier) {
            $this->addModifier($modifier);
        }

        return $this;
    }

    /**
     * @param PaymentModifier|array $modifier anything else is ignored
     */
    public function addModifier(mixed $modifier): self
    {
        if (is_array($modifier)) {
            $modifier = PaymentModifier::fromArray($modifier);
        }

        if ($modifier instanceof PaymentModifier) {
            $this->modifiers[] = $modifier;
        }

        return $this;
    }

    public function modifiersToArray(): array
    {
        return array_map(fn (PaymentModifier $modifier) => $modifier->toArray(), $this->modifiers);
    }

    public function modifier(string $type, ?string $code = null): ?PaymentModifier
    {
        foreach ($this->modifiers as $modifier) {
            if ($modifier->type() === $type && (!$code || $code === $modifier->code())) {
                return $modifier;
            }
        }

        return null;
    }

    public function toArray(): array
    {
        return self::arrayFilter([
            'agreement' => $this->agreement(),
            'agreementType' => $this->agreementType(),
            'reference' => $this->reference(),
            'description' => $this->description(),
            'amount' => $this->amount()?->toArray(),
            'shipping' => $this->shipping()?->toArray(),
            'items' => $this->itemsToArray(),
            'recurring' => $this->recurring()?->toArray(),
            'discount' => $this->discount()?->toArray(),
            'fields' => $this->fieldsToArray(),
            'subscribe' => $this->subscribe() ?: null,
            'allowPartial' => $this->allowPartial ?: null,
            'modifiers' => $this->modifiersToArray(),
        ]);
    }
}
