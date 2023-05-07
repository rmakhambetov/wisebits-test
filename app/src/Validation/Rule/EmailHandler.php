<?php

namespace App\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

final class EmailHandler implements RuleHandler
{
    private const INVALID_EMAIL = 'invalid_email';

    public static function rule(): string
    {
        return Email::class;
    }

    /**
     * @param ForbiddenValue $rule
     */
    public function handle(mixed $value, Rule $rule, Validator $validator): \Generator
    {
        if (null === $value) {
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            yield new Error(
                self::INVALID_EMAIL,
                'Value {value} is invalid email address',
                ['value' => $value]
            );
        }

        [, $domain] = explode('@', $value);
        if ($rule->getRepository()->isBlacklisted((string)$domain)) {
            yield new Error(
                self::INVALID_EMAIL,
                'Email {value} has forbidden domain',
                ['value' => $value]
            );
        }
    }
}
