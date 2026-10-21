<?php

namespace PlacetoPay\PlacetoPaySdk\Entities;

class Amount extends AmountBase
{
    /**
     * @var TaxDetail[]
     */
    protected array $taxes = [];
    /**
     * @var AmountDetail[]
     */
    protected array $details = [];

    /**
     * Details Available:
     * @var float
     * @see https://docs.placetopay.dev/checkout/tax-details#amount-details
     */
    protected float $discount;
    protected float $additional;
    protected float $vatDevolutionBase;
    protected float $shipping;
    protected float $handlingFee;
    protected float $insurance;
    protected float $giftWrap;
    protected float $subtotal;
    protected float $fee;
    protected float $tip;
    protected float $airline;
    protected float $interest;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (isset($data['taxes'])) {
            $this->setTaxes($data['taxes']);
        }
        if (isset($data['details'])) {
            $this->setDetails($data['details']);
        }
    }

    /**
     * @return TaxDetail[]
     */
    public function taxes(): array
    {
        return $this->taxes;
    }

    /**
     * @return AmountDetail[]
     */
    public function details(): array
    {
        return $this->details;
    }

    /**
     * @param array<TaxDetail|array> $taxes
     */
    public function setTaxes(array $taxes): static
    {
        $this->taxes = array_map(fn (TaxDetail|array $tax) => is_array($tax) ? new TaxDetail($tax) : $tax, $taxes);

        return $this;
    }

    /**
     * @param array<AmountDetail|array> $details
     */
    public function setDetails(array $details): static
    {
        $this->details = [];
        foreach ($details as $detail) {
            if (is_array($detail)) {
                $detail = new AmountDetail($detail);
            }

            $this->{$detail->kind()} = $detail->amount();
            $this->details[] = $detail;
        }

        return $this;
    }

    private function taxesToArray(): ?array
    {
        return array_map(fn (TaxDetail $tax) => $tax->toArray(), $this->taxes()) ?: null;
    }

    private function detailsToArray(): ?array
    {
        return array_map(fn (AmountDetail $detail) => $detail->toArray(), $this->details()) ?: null;
    }

    public function toArray(): array
    {
        return $this->arrayFilter(array_merge([
            'taxes' => $this->taxesToArray(),
            'details' => $this->detailsToArray(),
        ], parent::toArray()));
    }
}
