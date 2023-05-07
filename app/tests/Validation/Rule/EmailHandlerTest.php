<?php

namespace Tests\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule\Email;
use App\Validation\Rule\EmailHandler;
use PHPUnit\Framework\TestCase;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use Tests\Repository\Stubs\SimpleBlacklistRepository;
use Tests\Validation\Stubs\InvalidHandler;

final class EmailHandlerTest extends TestCase
{
    /**
     * @param        mixed $value
     * @dataProvider provideValidType
     */
    public function testValid(mixed $value): void
    {
        $validator = $this->createValidator();
        $handler = new EmailHandler();
        $rule = new Email(new SimpleBlacklistRepository());

        $errors = $handler->handle($value, $rule, $validator);
        self::assertSame([], iterator_to_array($errors));
    }

    /**
     * @param        mixed $value
     * @param        mixed $error
     * @dataProvider provideInvalidType
     */
    public function testInvalidType(mixed $value, mixed $error): void
    {
        $validator = $this->createValidator();
        $handler = new EmailHandler();
        $rule = new Email(new SimpleBlacklistRepository());

        $errors = $handler->handle($value, $rule, $validator);
        self::assertEquals(
            [
            new Error(
                'invalid_email',
                $error,
                ['value' => $value]
            )
            ],
            iterator_to_array($errors)
        );
    }

    private function createValidator(): Validator
    {
        return new Validator(
            new InMemoryRuleHandlerRegistry(
                [
                new InvalidHandler(),
                ]
            )
        );
    }

  /**
   * @psalm-return \Generator<int, array{mixed}>
   */
    public static function provideValidType(): \Generator
    {
        yield ['test1@test.ru'];
        yield ['test2@test.ru'];
        yield ['test3@test.ru'];
    }

  /**
   * @psalm-return \Generator<int, array{mixed}>
   */
    public static function provideInvalidType(): \Generator
    {
        yield ['test1@test', 'Value {value} is invalid email address'];
        yield ['test2@.ru', 'Value {value} is invalid email address'];
        yield ['@test.ru', 'Value {value} is invalid email address'];
        yield ['test1@domain.org', 'Email {value} has forbidden domain'];
    }
}
