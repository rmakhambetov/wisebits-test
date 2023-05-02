<?php

namespace App\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

final class UniqueHandler implements RuleHandler
{
    private const NON_UNIQUE_VALUE = 'non_unique_value';

    public static function rule(): string
    {
        return Unique::class;
    }

    /**
     * @param Unique $rule
     */
    public function handle(mixed $value, Rule $rule, Validator $validator): \Generator
    {
        if (null === $value) {
            return;
        }

        if ($rule->getRepository()->isUnique($rule->id, $rule->name, $value)) {
            return;
        }

        yield new Error(
            self::NON_UNIQUE_VALUE,
            'Value {value} is not unique {name}',
            [
            'value' => $value,
            'name' => $rule->name,
            ]
        );
    }
}
