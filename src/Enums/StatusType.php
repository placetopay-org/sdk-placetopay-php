<?php

namespace PlacetoPay\PlacetoPaySdk\Enums;

enum StatusType: string
{
    case OK = 'OK';
    case FAILED = 'FAILED';
    case APPROVED = 'APPROVED';
    case APPROVED_PARTIAL = 'APPROVED_PARTIAL';
    case REJECTED = 'REJECTED';
    case PENDING = 'PENDING';
    case PENDING_VALIDATION = 'PENDING_VALIDATION';
    case PENDING_CONFIRMATION = 'PENDING_CONFIRMATION';
    case PARTIAL_EXPIRED = 'PARTIAL_EXPIRED';
    case REFUNDED = 'REFUNDED';
    case ERROR = 'ERROR';
    case UNKNOWN = 'UNKNOWN';

    /**
     * Resolves a status by its name, any value not recognized maps to UNKNOWN.
     */
    public static function fromName(string $name): self
    {
        return self::tryFrom($name) ?? self::UNKNOWN;
    }
}
