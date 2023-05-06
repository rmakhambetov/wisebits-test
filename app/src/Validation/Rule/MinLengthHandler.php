<?php

namespace App\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

class MinLengthHandler implements RuleHandler
{
    private const MIN_LEN = 'min_len';

    public static function rule(): string
    {
        return MinLength::class;
    }

    /**
     * @param MinLength $rule
     */
    public function handle(mixed $value, Rule $rule, Validator $validator): \Generator
    {
        if (null === $value) {
            return;
        }

        if (strlen($value) >= $rule->minLength) {
            return;
        }

        yield new Error(
            self::MIN_LEN,
            'Len of {value} must be greater or equals than {len}',
            [
            'value' => $value,
            'min_len' => $rule->minLength,
            ]
        );
    }
}
