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
        return ForbiddenValue::class;
    }

    /**
     * @param ForbiddenValue $rule
     */
    public function handle(mixed $value, Rule $rule, Validator $validator): \Generator
    {
        if (null === $value) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        yield new Error(
            self::INVALID_EMAIL,
            'Value {value} is invalid email address',
            ['value' => $value]
        );
    }
}
