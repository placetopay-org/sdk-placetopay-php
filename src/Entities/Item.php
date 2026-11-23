<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

use PlacetoPay\PlacetoPaySdk\Contracts\Entity;

class Item extends Entity
{
    public function __construct(
        protected string $sku = '',
        protected string $name = '',
        protected string $category = '',
        protected string $qty = '',
        protected string $price = '',
        protected string $tax = '',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            sku: $data['sku'] ?? '',
            name: $data['name'] ?? '',
            category: $data['category'] ?? '',
            qty: $data['qty'] ?? '',
            price: $data['price'] ?? '',
            tax: $data['tax'] ?? '',
        );
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function qty(): string
    {
        return $this->qty;
    }

    public function price(): string
    {
        return $this->price;
    }

    public function tax(): string
    {
        return $this->tax;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'sku' => $this->sku(),
            'name' => $this->name(),
            'category' => $this->category(),
            'qty' => $this->qty(),
            'price' => $this->price(),
            'tax' => $this->tax(),
        ]);
    }
}
