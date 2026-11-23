<?php

namespace PlacetoPay\PlacetoPaySdk\Exceptions;

use Exception;
use Throwable;

class PlacetoPayException extends Exception
{
    public static function readException(Throwable $e): string
    {
        return $e->getMessage() . ' ON ' . $e->getFile() . ' LINE ' . $e->getLine() . ' [' . $e::class . ']';
    }

    public static function forDataNotProvided(string $message = ''): self
    {
        return new self($message);
    }
}
