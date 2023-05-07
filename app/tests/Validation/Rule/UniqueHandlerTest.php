<?php

namespace Tests\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule\Unique;
use App\Validation\Rule\UniqueHandler;
use PHPUnit\Framework\TestCase;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use Tests\Repository\Stubs\SimpleUniqueRepository;
use Tests\Validation\Stubs\InvalidHandler;

final class UniqueHandlerTest extends TestCase
{
    /**
     * @param        mixed $value
     * @dataProvider provideValidType
     */
    public function testValid(mixed $value): void
    {
        $validator = $this->createValidator();
        $handler = new UniqueHandler();
        $rule = new Unique($value[0], 'name', new SimpleUniqueRepository());

        $errors = $handler->handle($value[1], $rule, $validator);
        self::assertSame([], iterator_to_array($errors));
    }

    /**
     * @param        mixed $value
     * @dataProvider provideInvalidType
     */
    public function testInvalidType(mixed $value): void
    {
        $validator = $this->createValidator();
        $handler = new UniqueHandler();
        $rule = new Unique($value[0], 'name', new SimpleUniqueRepository());

        $errors = $handler->handle($value[1], $rule, $validator);
        self::assertEquals(
            [
            new Error(
                'non_unique_value',
                'Value {value} is not unique {name}',
                [
                'value' => $value[1],
                'name' => $rule->name,
                ]
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
        yield [[1, 'roman']];
        yield [[2, 'oleg']];
        yield [[3, 'test']];
    }

  /**
   * @psalm-return \Generator<int, array{mixed}>
   */
    public static function provideInvalidType(): \Generator
    {
        yield [[1, 'oleg']];
        yield [[2, 'roman']];
        yield [[3, 'roman']];
        yield [[4, 'oleg']];
    }
}
