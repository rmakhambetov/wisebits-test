<?php

declare(strict_types=1);

namespace App\Validation;

final class Validator
{
    private RuleHandlerRegistry $ruleHandlerRegistry;

    public function __construct(RuleHandlerRegistry $ruleHandlerRegistry)
    {
        $this->ruleHandlerRegistry = $ruleHandlerRegistry;
    }

    /**
     * @return       Error[]
     * @psalm-return list<Error>
     */
    public function validate(mixed $value, Rule $rule): array
    {
        $errors = $this
            ->ruleHandlerRegistry
            ->getRuleHandler(\get_class($rule))
            ->handle($value, $rule, $this);

        return iterator_to_array($errors, false);
    }
}
