<?php

declare(strict_types=1);

namespace App\Validation\Rule;

use App\Validation\Rule;

/**
 * @psalm-immutable
 */
final class ObjectProperties implements Rule
{
    /**
     * @psalm-var non-empty-array<string, Rule>
     */
    public array $propertyRules;

    /**
     * @psalm-param non-empty-array<string, Rule> $propertyRules
     */
    public function __construct(array $propertyRules)
    {
        $this->propertyRules = $propertyRules;
    }
}
