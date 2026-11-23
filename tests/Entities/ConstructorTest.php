<?php

namespace PlacetoPay\PlacetoPaySdk\Tests\Entities;

use PlacetoPay\PlacetoPaySdk\Entities\Amount;
use PlacetoPay\PlacetoPaySdk\Entities\DispersionPayment;
use PlacetoPay\PlacetoPaySdk\Entities\Instrument;
use PlacetoPay\PlacetoPaySdk\Entities\Person;
use PlacetoPay\PlacetoPaySdk\Entities\Token;
use PlacetoPay\PlacetoPaySdk\Exceptions\PlacetoPayException;
use PlacetoPay\PlacetoPaySdk\Message\CollectRequest;
use PlacetoPay\PlacetoPaySdk\Message\RedirectRequest;
use PlacetoPay\PlacetoPaySdk\Tests\BaseTestCase;

class ConstructorTest extends BaseTestCase
{
    public function testItBuildsTheSameRequestFromConstructorAndFromArray(): void
    {
        $data = [
            'payment' => [
                'reference' => 'REF_001',
                'description' => 'Testing payment',
                'amount' => ['currency' => 'USD', 'total' => 120],
            ],
            'expiration' => '2030-01-01T00:00:00+00:00',
            'returnUrl' => 'https://example.com/return',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Testing',
        ];

        $request = new RedirectRequest(
            returnUrl: 'https://example.com/return',
            ipAddress: '127.0.0.1',
            userAgent: 'Testing',
            payment: new DispersionPayment(
                reference: 'REF_001',
                description: 'Testing payment',
                amount: new Amount(currency: 'USD', total: 120),
            ),
            expiration: '2030-01-01T00:00:00+00:00',
        );

        $this->assertEquals(RedirectRequest::fromArray($data)->toArray(), $request->toArray());
    }

    public function testItBuildsACollectRequestWithNamedArguments(): void
    {
        $request = new CollectRequest(
            instrument: new Instrument(token: new Token(token: 'a_token')),
            provider: 'PROVIDER',
            payer: new Person(document: '1040035000', documentType: 'CC', name: 'John'),
            returnUrl: 'https://example.com/return',
        );

        $this->assertSame('John', $request->payer()->name());
        $this->assertSame('a_token', $request->instrument()->token()->token());
        $this->assertSame('PROVIDER', $request->provider());
        $this->assertSame('https://example.com/return', $request->returnUrl());
    }

    public function testItRequiresThePayerOnCollectRequest(): void
    {
        $this->expectException(PlacetoPayException::class);
        $this->expectExceptionMessage('The payer is required to collect');

        new CollectRequest(instrument: new Instrument());
    }
}
