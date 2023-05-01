<?php

declare(strict_types=1);

namespace Validation\Rule;

use Validation\Rule;

/**
 * @psalm-immutable
 */
final class Any implements Rule
{
    /**
     * @var Rule[]
     * @psalm-var non-empty-list<Rule>
     */
    public array $rules;

    /**
     * @psalm-param non-empty-list<Rule> $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }
}
