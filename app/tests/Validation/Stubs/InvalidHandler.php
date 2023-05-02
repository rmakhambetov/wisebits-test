<?php

namespace Tests\Validation\Stubs;

use App\Validation\RuleHandler;
use App\Validation\Rule;
use App\Validation\Validator;
use App\Validation\Error;

final class InvalidHandler implements RuleHandler
{
    private static ?Error $error = null;

    public static function rule(): string
    {
        return Invalid::class;
    }

    public static function error(): Error
    {
        return self::$error ??= new Error('invalid', 'message template', ['variable' => 'value']);
    }

    public function handle($value, Rule $rule, Validator $validator): \Generator
    {
        yield self::error();
    }
}
