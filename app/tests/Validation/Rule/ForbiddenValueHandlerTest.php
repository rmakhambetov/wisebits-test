<?php

namespace Tests\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule\ForbiddenValue;
use App\Validation\Rule\ForbiddenValueHandler;
use PHPUnit\Framework\TestCase;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use Tests\Repository\Stubs\SimpleBlacklistRepository;
use Tests\Validation\Stubs\InvalidHandler;

final class ForbiddenValueHandlerTest extends TestCase
{
    public function testValid(): void
    {
        $validator = $this->createValidator();
        $handler = new ForbiddenValueHandler();
        $rule = new ForbiddenValue(new SimpleBlacklistRepository());

        $errors = $handler->handle(null, $rule, $validator);
        self::assertSame([], iterator_to_array($errors));
    }

    /**
     * @param        mixed $value
     * @dataProvider provideInvalidType
     */
    public function testInvalidType(mixed $value): void
    {
        $validator = $this->createValidator();
        $handler = new ForbiddenValueHandler();
        $rule = new ForbiddenValue(new SimpleBlacklistRepository());

        $errors = $handler->handle($value, $rule, $validator);
        self::assertEquals(
            [
            new Error(
                'forbidden_value',
                'Value {value} is forbidden',
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
        yield ['test'];
        yield ['0'];
        yield [0];
        yield [''];
    }

  /**
   * @psalm-return \Generator<int, array{mixed}>
   */
    public static function provideInvalidType(): \Generator
    {
        yield ['curseword1'];
        yield ['curseword2'];
        yield [123];
    }
}
