<?php

namespace PlacetoPay\PlacetoPaySdk;

use PlacetoPay\PlacetoPaySdk\Exceptions\PlacetoPayException;
use PlacetoPay\PlacetoPaySdk\Helpers\Settings;
use PlacetoPay\PlacetoPaySdk\Message\CollectRequest;
use PlacetoPay\PlacetoPaySdk\Message\Notification;
use PlacetoPay\PlacetoPaySdk\Message\RedirectInformation;
use PlacetoPay\PlacetoPaySdk\Message\RedirectRequest;
use PlacetoPay\PlacetoPaySdk\Message\RedirectResponse;
use PlacetoPay\PlacetoPaySdk\Message\ReverseResponse;

class PlacetoPay
{
    protected readonly Settings $settings;

    /**
     * @throws PlacetoPayException
     */
    public function __construct(array $data)
    {
        $this->settings = new Settings($data);
    }

    /**
     * Accepts mixed so anything other than an array or RedirectRequest raises a PlacetoPayException.
     * @param RedirectRequest|array $redirectRequest
     * @throws PlacetoPayException
     */
    public function request(mixed $redirectRequest): RedirectResponse
    {
        $redirectRequest = match (true) {
            is_array($redirectRequest) => new RedirectRequest($redirectRequest),
            $redirectRequest instanceof RedirectRequest => $redirectRequest,
            default => throw PlacetoPayException::forDataNotProvided('Wrong class request'),
        };

        return $this->settings->carrier()->request($redirectRequest);
    }

    public function query(int $requestId): RedirectInformation
    {
        return $this->settings->carrier()->query($requestId);
    }

    /**
     * Accepts mixed so anything other than an array or CollectRequest raises a PlacetoPayException.
     * @param CollectRequest|array $collectRequest
     * @throws PlacetoPayException
     */
    public function collect(mixed $collectRequest): RedirectInformation
    {
        $collectRequest = match (true) {
            is_array($collectRequest) => new CollectRequest($collectRequest),
            $collectRequest instanceof CollectRequest => $collectRequest,
            default => throw new PlacetoPayException('Wrong collect request'),
        };

        return $this->settings->carrier()->collect($collectRequest);
    }

    public function reverse(int $internalReference): ReverseResponse
    {
        return $this->settings->carrier()->reverse($internalReference);
    }

    public function readNotification(array $data): Notification
    {
        return new Notification($data, $this->settings->tranKey());
    }
}
