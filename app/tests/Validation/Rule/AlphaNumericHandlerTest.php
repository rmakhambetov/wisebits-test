<?php

namespace Tests\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule\AlphaNumeric;
use App\Validation\Rule\AlphaNumericHandler;
use PHPUnit\Framework\TestCase;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use Tests\Validation\Stubs\InvalidHandler;

final class AlphaNumericHandlerTest extends TestCase
{
    /**
     * @param        mixed $value
     * @dataProvider provideValidType
     */
    public function testValid(mixed $value): void
    {
      $validator = $this->createValidator();
      $handler = new AlphaNumericHandler();
      $rule = new AlphaNumeric();

      $errors = $handler->handle($value, $rule, $validator);
      self::assertSame([], iterator_to_array($errors));
    }

    /**
     * @param        mixed $value
     * @dataProvider provideInvalidType
     */
    public function testInvalidType(mixed $value): void
    {
      $validator = $this->createValidator();
      $handler = new AlphaNumericHandler();
      $rule = new AlphaNumeric();

      $errors = $handler->handle($value, $rule, $validator);
      self::assertEquals(
        [
        new Error(
          'non_alphanumeric',
          'Value {value} must be alphanumeric',
          ['value' => $value]
        )
        ],
        iterator_to_array($errors));
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
    yield ['asd'];
    yield ['123'];
    yield ['ads123'];
  }

  /**
   * @psalm-return \Generator<int, array{mixed}>
   */
  public static function provideInvalidType(): \Generator
  {
    yield ['asd-'];
    yield ['asd-1'];
    yield ['asd.1'];
  }
}
