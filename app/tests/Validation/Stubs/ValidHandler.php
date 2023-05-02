<?php

namespace Tests\Validation\Stubs;

use App\Validation\RuleHandler;
use App\Validation\Rule;
use App\Validation\Validator;

final class ValidHandler implements RuleHandler
{
    public static function rule(): string
    {
        return Valid::class;
    }

    public function handle($value, Rule $rule, Validator $validator): \Generator
    {
        yield from [];
    }
}
