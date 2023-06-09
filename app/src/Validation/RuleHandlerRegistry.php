<?php

declare(strict_types=1);

namespace App\Validation;

interface RuleHandlerRegistry
{
    /**
     * @template     T of Rule
     * @psalm-param  class-string<T> $rule
     * @psalm-return RuleHandler<T>
     *
     * @throws Exception\RuleHandlerNotFound
     */
    public function getRuleHandler(string $rule): RuleHandler;
}
