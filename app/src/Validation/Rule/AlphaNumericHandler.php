<?php

namespace App\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

final class AlphaNumericHandler implements RuleHandler
{
    private const NON_ALPHANUMERIC = 'non_alphanumeric';

    public static function rule(): string
    {
        return AlphaNumeric::class;
    }

    public function handle(mixed $value, Rule $rule, Validator $validator): \Generator
    {
        if (null === $value) {
            return;
        }

        if (ctype_alnum($value)) {
            return;
        }

        yield new Error(
            self::NON_ALPHANUMERIC,
            'Value {value} must be alphanumeric',
            ['value' => $value]
        );
    }
}
