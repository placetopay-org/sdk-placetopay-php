<?php

namespace PlacetoPay\PlacetoPaySdk\Tests\Entities;

use PlacetoPay\PlacetoPaySdk\Entities\Credit;
use PlacetoPay\PlacetoPaySdk\Tests\BaseTestCase;

class CreditTest extends BaseTestCase
{
    public function testItHandlesACreditWithoutAllItsData()
    {
        $credit = Credit::fromArray(['installment' => 3]);

        $this->assertNull($credit->code());
        $this->assertSame(['installment' => 3], $credit->toArray());
    }

    public function testItChargesASingleInstallmentByDefault()
    {
        $this->assertSame(1, (Credit::fromArray(['code' => '1', 'type' => '02', 'groupCode' => 'C']))->installment());
    }

    public function testItCreatesASingleInstallmentCredit()
    {
        $this->assertSame(['installment' => 1], Credit::oneInstallment()->toArray());
        $this->assertSame(
            ['code' => '1', 'type' => '02', 'groupCode' => 'C', 'installment' => 1],
            Credit::oneInstallment(code: '1', type: '02', groupCode: 'C')->toArray()
        );
    }
}
