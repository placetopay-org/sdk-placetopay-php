<?php

namespace PlacetoPay\PlacetoPaySdk\Traits;

use PlacetoPay\PlacetoPaySdk\Entities\Status;
use PlacetoPay\PlacetoPaySdk\Enums\StatusType;

trait StatusTrait
{
    protected Status $status;

    public function status(): Status
    {
        return $this->status;
    }

    public function isApproved(): bool
    {
        return $this->status()->isApproved();
    }

    public function isSuccessful(): bool
    {
        return !in_array($this->status()->type(), [StatusType::ERROR, StatusType::FAILED], true);
    }

    public function isRejected(): bool
    {
        return $this->status()->isRejected();
    }
}
