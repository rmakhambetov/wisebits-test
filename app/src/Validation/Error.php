<?php

declare(strict_types=1);

namespace App\Validation;

/**
 * @psalm-immutable
 */
final class Error
{
    private const INVALID_TYPE = 'invalid_type';

    public string $code;

    public string $message;

    /**
     * @psalm-var array<string, mixed>
     */
    public array $variables;

    public string $path;

    /**
     * @psalm-param array<string, mixed> $variables
     */
    public function __construct(string $code, string $message, array $variables = [], string $path = '')
    {
        $this->code = $code;
        $this->message = $message;
        $this->variables = $variables;
        $this->path = $path;
    }

    public static function invalidType(mixed $value, string $expectedType, string ...$expectedTypes): self
    {
        return new self(
            self::INVALID_TYPE,
            'Invalid type {actual_type}, expected {expected_types}',
            [
            'actual_type' => get_debug_type($value),
            'expected_types' => implode('|', [$expectedType, ...$expectedTypes]),
            ]
        );
    }

    public function atProperty(string $property): self
    {
        $error = clone $this;
        $error->path = sprintf('.%s%s', $property, $this->path);

        return $error;
    }

    public function atOffset(string $offset): self
    {
        $error = clone $this;
        $error->path = sprintf('[%s]%s', $offset, $this->path);

        return $error;
    }
}
