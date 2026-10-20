<?php

namespace PlacetoPay\PlacetoPaySdk\Contracts;

use PlacetoPay\PlacetoPaySdk\Helpers\Settings;
use PlacetoPay\PlacetoPaySdk\Message\CollectRequest;
use PlacetoPay\PlacetoPaySdk\Message\RedirectInformation;
use PlacetoPay\PlacetoPaySdk\Message\RedirectRequest;
use PlacetoPay\PlacetoPaySdk\Message\RedirectResponse;
use PlacetoPay\PlacetoPaySdk\Message\ReverseResponse;

abstract class Carrier
{
    public function __construct(protected readonly Settings $settings)
    {
    }

    abstract public function request(RedirectRequest $redirectRequest): RedirectResponse;

    abstract public function query(int $requestId): RedirectInformation;

    abstract public function collect(CollectRequest $collectRequest): RedirectInformation;

    abstract public function reverse(int $internalReference): ReverseResponse;
}
