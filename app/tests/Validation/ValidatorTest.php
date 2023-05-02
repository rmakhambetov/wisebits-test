<?php

namespace Tests\Validation;

use PHPUnit\Framework\TestCase;
use App\Validation\Validator;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use Tests\Validation\Stubs\Invalid;
use Tests\Validation\Stubs\InvalidHandler;
use Tests\Validation\Stubs\Valid;
use Tests\Validation\Stubs\ValidHandler;

final class ValidatorTest extends TestCase
{
    public function testValidateInvalid(): void
    {
        $validator = new Validator(
            new InMemoryRuleHandlerRegistry(
                [
                new InvalidHandler(),
                ]
            )
        );

        $errors = $validator->validate(1, new Invalid());

        self::assertSame([InvalidHandler::error()], $errors);
    }

    public function testValidateValid(): void
    {
        $validator = new Validator(
            new InMemoryRuleHandlerRegistry(
                [
                new ValidHandler(),
                ]
            )
        );

        $errors = $validator->validate(1, new Valid());

        self::assertSame([], $errors);
    }
}
