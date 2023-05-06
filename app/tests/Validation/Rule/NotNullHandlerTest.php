<?php

namespace Tests\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule\NotNull;
use App\Validation\Rule\NotNullHandler;
use PHPUnit\Framework\TestCase;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use Tests\Validation\Stubs\InvalidHandler;

final class NotNullHandlerTest extends TestCase
{
    public function testNull(): void
    {
      $validator = $this->createValidator();
      $handler = new NotNullHandler();
      $rule = new NotNull();

      $errors = $handler->handle(null, $rule, $validator);
      self::assertEquals(
        [
          new Error(
            'not_null',
            'Value must not be a null'
          ),
        ],
        iterator_to_array($errors)
      );
    }

  /**
   * @param        mixed $value
   * @dataProvider provideValidType
   */
    public function testNotNull(mixed $value): void
    {
      $validator = $this->createValidator();
      $handler = new NotNullHandler();
      $rule = new NotNull();

      $errors = $handler->handle($value, $rule, $validator);
      self::assertSame([], iterator_to_array($errors));
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
    yield ['test'];
    yield ['0'];
    yield [0];
    yield [''];
  }
}
