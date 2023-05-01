<?php

declare(strict_types=1);

namespace Validation\Exception;

final class RuleHandlerNotFound extends \RuntimeException
{
    /**
     * @psalm-param class-string<\Validation\Rule> $rule
     */
    public static function forRule(string $rule, ?\Throwable $previous = null): self
    {
        return new self(sprintf('No handler found for rule "%s"', $rule), 0, $previous);
    }
}
