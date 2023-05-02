<?php

declare(strict_types=1);

namespace App\Validation\Exception;

final class RuleHandlerNotFound extends \RuntimeException
{
    public static function forRule(string $rule, ?\Throwable $previous = null): self
    {
        return new self(sprintf('No handler found for rule "%s"', $rule), 0, $previous);
    }
}
