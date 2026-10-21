<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;
use PlacetoPay\PlacetoPaySdk\Traits\FieldsTrait;

class Payment extends Entity
{
    use FieldsTrait;

    protected string $reference;
    protected string $description = '';
    protected ?Amount $amount = null;
    protected bool $allowPartial = false;
    protected ?Person $shipping = null;
    /**
     * @var Item[]
     */
    protected array $items = [];
    protected ?Recurring $recurring = null;
    protected ?Discount $discount = null;
    protected ?Instrument $instrument = null;
    public bool $subscribe = false;
    protected ?int $agreement = null;
    protected string $agreementType = '';

    /** @var PaymentModifier[] */
    protected array $modifiers = [];

    public function __construct(array $data = [])
    {
        $this->load($data, ['reference', 'description', 'allowPartial', 'subscribe', 'agreement', 'agreementType']);

        $this->loadEntity($data['amount'] ?? null, 'amount', Amount::class);
        $this->loadEntity($data['recurring'] ?? null, 'recurring', Recurring::class);
        $this->loadEntity($data['shipping'] ?? null, 'shipping', Person::class);
        $this->loadEntity($data['discount'] ?? null, 'discount', Discount::class);

        if (isset($data['items'])) {
            $this->setItems($data['items']);
        }
        if (isset($data['fields'])) {
            $this->setFields($data['fields']);
        }
        if (isset($data['modifiers']) && is_array($data['modifiers'])) {
            $this->setModifiers($data['modifiers']);
        }
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
            fn (Item|array $data) => is_array($data) ? new Item($data) : $data,
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
            $modifier = new PaymentModifier($modifier);
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
