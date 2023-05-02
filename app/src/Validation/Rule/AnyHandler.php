<?php

declare(strict_types=1);

namespace App\Validation\Rule;

use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

/**
 * @implements RuleHandler<Any>
 */
final class AnyHandler implements RuleHandler
{
    public static function rule(): string
    {
        return Any::class;
    }

    /**
     * @param Any $rule
     */
    public function handle($value, Rule $rule, Validator $validator): \Generator
    {
        foreach ($rule->rules as $oneRule) {
            foreach ($validator->validate($value, $oneRule) as $error) {
                yield $error;

                return;
            }
        }
    }
}
