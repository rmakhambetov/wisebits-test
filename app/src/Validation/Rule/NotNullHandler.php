<?php

namespace App\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

final class NotNullHandler implements RuleHandler
{
    private const NOT_NULL = 'not_null';

    public static function rule(): string
    {
        return MinLength::class;
    }

    /**
     * @param NotNull $rule
     */
    public function handle(mixed $value, Rule $rule, Validator $validator): \Generator
    {
        if (null !== $value) {
            return;
        }

        yield new Error(
            self::NOT_NULL,
            'Value must not be a null'
        );
    }
}
