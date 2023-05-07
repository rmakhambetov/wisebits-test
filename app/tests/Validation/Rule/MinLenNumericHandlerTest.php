<?php

namespace Tests\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule\AlphaNumeric;
use App\Validation\Rule\AlphaNumericHandler;
use App\Validation\Rule\MinLength;
use App\Validation\Rule\MinLengthHandler;
use PHPUnit\Framework\TestCase;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use Tests\Validation\Stubs\InvalidHandler;

final class MinLenNumericHandlerTest extends TestCase
{
    /**
     * @param        mixed $value
     * @param        int $len
     * @dataProvider provideValidType
     */
    public function testValid(mixed $value, int $len): void
    {
        $validator = $this->createValidator();
        $handler = new MinLengthHandler();
        $rule = new MinLength($len);

        $errors = $handler->handle($value, $rule, $validator);
        self::assertSame([], iterator_to_array($errors));
    }

    /**
     * @param        mixed $value
     * @param        int $len
     * @dataProvider provideInvalidType
     */
    public function testInvalidType(mixed $value, int $len): void
    {
        $validator = $this->createValidator();
        $handler = new MinLengthHandler();
        $rule = new MinLength($len);

        $errors = $handler->handle($value, $rule, $validator);
        self::assertEquals(
            [
            new Error(
                'min_len',
                'Len of {value} must be greater or equals than {len}',
                ['value' => $value, 'min_len' => $len]
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
        yield ['asd', 0];
        yield ['123', 3];
        yield ['ads123', 4];
    }

  /**
   * @psalm-return \Generator<int, array{mixed}>
   */
    public static function provideInvalidType(): \Generator
    {
        yield ['asd', 4];
        yield ['123', 5];
        yield ['ads123', 10];
    }
}
