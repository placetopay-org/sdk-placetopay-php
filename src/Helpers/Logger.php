<?php

namespace PlacetoPay\PlacetoPaySdk\Helpers;

use Psr\Log\LoggerInterface;
use Stringable;

/**
 * @method void emergency(string $message, array $context = [])
 * @method void alert(string $message, array $context = [])
 * @method void critical(string $message, array $context = [])
 * @method void error(string $message, array $context = [])
 * @method void warning(string $message, array $context = [])
 * @method void notice(string $message, array $context = [])
 * @method void info(string $message, array $context = [])
 * @method void debug(string $message, array $context = [])
 */
class Logger
{
    public function __construct(protected readonly ?LoggerInterface $logger = null)
    {
    }

    public function log(string $level, string|Stringable $message, array $context = []): void
    {
        $this->logger?->log($level, '(REDIRECTION_LOG) ' . $message, $this->cleanUp($context));
    }

    public function __call(string $name, array $arguments): void
    {
        $this->log($name, $arguments[0], $arguments[1] ?? []);
    }

    public function cleanUp(mixed $context): array
    {
        return $context ?: [];
    }
}
