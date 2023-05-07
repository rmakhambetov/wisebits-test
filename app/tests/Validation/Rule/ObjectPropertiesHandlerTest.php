<?php

namespace Tests\Validation\Rule;

use PHPUnit\Framework\TestCase;
use App\Validation\Rule\ObjectProperties;
use App\Validation\Rule\ObjectPropertiesHandler;
use App\Validation\Error;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use Tests\Validation\User;
use Tests\Validation\Stubs\Invalid;
use Tests\Validation\Stubs\InvalidHandler;
use Tests\Validation\Stubs\Valid;

final class ObjectPropertiesHandlerTest extends TestCase
{
    /**
     * @param        mixed $value
     * @dataProvider provideInvalidType
     */
    public function testInvalidType($value): void
    {
        $validator = $this->createValidator();
        $handler = new ObjectPropertiesHandler();
        $rule = new ObjectProperties(
            [
            'name' => new Valid(),
            ]
        );

        $errors = $handler->handle($value, $rule, $validator);

        self::assertEquals(
            [
            Error::invalidType($value, 'object'),
            ],
            iterator_to_array($errors)
        );
    }

    /**
     * @psalm-return \Generator<int, array{mixed}>
     */
    public static function provideInvalidType(): \Generator
    {
        yield ['a'];
        yield [1];
    }

    public function testInvalid(): void
    {
        $validator = $this->createValidator();
        $handler = new ObjectPropertiesHandler();
        $rule = new ObjectProperties(
            [
            'name' => new Invalid(),
            ]
        );
        $user = new User();

        $errors = $handler->handle($user, $rule, $validator);

        self::assertEquals(
            [
            InvalidHandler::error()->atProperty('name'),
            ],
            iterator_to_array($errors)
        );
    }

    public function testNullValid(): void
    {
        $validator = $this->createValidator();
        $handler = new ObjectPropertiesHandler();
        $rule = new ObjectProperties(
            [
            'name' => new Invalid(),
            ]
        );

        $errors = $handler->handle(null, $rule, $validator);

        self::assertSame([], iterator_to_array($errors));
    }

    public function testPropertyDoesNotExist(): void
    {
        $validator = $this->createValidator();
        $handler = new ObjectPropertiesHandler();
        $rule = new ObjectProperties(
            [
            'lastname' => new Invalid(),
            ]
        );
        $person = new User();

        $errors = $handler->handle($person, $rule, $validator);

        self::assertEquals(
            [
            new Error(
                'property_does_not_exists',
                'Property {property} does not exist in class {class}.',
                [
                'property' => 'lastname',
                'class' => 'Tests\Validation\User',
                ]
            ),
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
}
