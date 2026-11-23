<?php

namespace PlacetoPay\PlacetoPaySdk\Tests\Enums;

use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Enums\StatusType;
use PlacetoPay\PlacetoPaySdk\Tests\BaseTestCase;

class StatusTypeTest extends BaseTestCase
{
    public function testItResolvesUnknownStatusesFromTheService()
    {
        $this->assertSame(StatusType::APPROVED, StatusType::fromName('APPROVED'));
        $this->assertSame(StatusType::UNKNOWN, StatusType::fromName('SOMETHING_NEW'));
        $this->assertSame(StatusType::PENDING_CONFIRMATION, StatusType::fromName('PENDING_CONFIRMATION'));
        $this->assertSame(StatusType::PARTIAL_EXPIRED, StatusType::fromName('PARTIAL_EXPIRED'));
    }

    public function testTheStatusEntityExposesItsType()
    {
        $status = Status::quick(StatusType::REJECTED, '05');

        $this->assertSame(StatusType::REJECTED, $status->type());
        $this->assertSame('REJECTED', $status->status());
        $this->assertTrue($status->isRejected());
        $this->assertSame(StatusType::ERROR, (Status::fromArray([]))->type());
    }
}
