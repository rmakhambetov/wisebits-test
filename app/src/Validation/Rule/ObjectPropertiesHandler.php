<?php

declare(strict_types=1);

namespace App\Validation\Rule;

use App\Validation\Error;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\Validator;

/**
 * @implements RuleHandler<ObjectProperties>
 */
final class ObjectPropertiesHandler implements RuleHandler
{
    private const PROPERTY_DOES_NOT_EXIST = 'property_does_not_exists';

    public static function rule(): string
    {
        return ObjectProperties::class;
    }

    /**
     * @param ObjectProperties $rule
     */
    public function handle($value, Rule $rule, Validator $validator): \Generator
    {
        if ($value === null) {
            return;
        }

        if (!\is_object($value)) {
            yield Error::invalidType($value, 'object');

            return;
        }

        foreach ($rule->propertyRules as $property => $propertyRule) {
            if (!property_exists($value, $property)) {
                yield new Error(
                    self::PROPERTY_DOES_NOT_EXIST,
                    'Property {property} does not exist in class {class}.',
                    [
                        'property' => $property,
                        'class' => \get_class($value),
                    ]
                );

                continue;
            }

            foreach ($validator->validate($value->{$property}, $propertyRule) as $propertyError) {
                yield $propertyError->atProperty($property);
            }
        }
    }
}
