<?php

namespace App\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

final class ForbiddenValueHandler implements RuleHandler
{
    private const FORBIDDEN_VALUE = 'forbidden_value';

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

        if ($rule->getRepository()->isBlacklisted($value)) {
            return;
        }

        yield new Error(
            self::FORBIDDEN_VALUE,
            'Value {value} is forbidden',
            ['value' => $value]
        );
    }
}
