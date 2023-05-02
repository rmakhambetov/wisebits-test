<?php

declare(strict_types=1);

namespace App\Validation\RuleHandlerRegistry;

use App\Validation\Exception;
use App\Validation\Rule;
use App\Validation\RuleHandler;
use App\Validation\RuleHandlerRegistry;

final class InMemoryRuleHandlerRegistry implements RuleHandlerRegistry
{
    /**
     * @psalm-var array<class-string<Rule>, RuleHandler>
     */
    private array $ruleHandlers = [];

    /**
     * @psalm-param iterable<RuleHandler> $ruleHandlers
     */
    public function __construct(iterable $ruleHandlers)
    {
        foreach ($ruleHandlers as $ruleHandler) {
            $this->ruleHandlers[$ruleHandler::rule()] = $ruleHandler;
        }
    }

    /**
     * @template     T of Rule
     * @psalm-param  class-string<T> $rule
     * @psalm-return RuleHandler<T>
     */
    public function getRuleHandler(string $rule): RuleHandler
    {
        if (isset($this->ruleHandlers[$rule])) {
            /**
 * @psalm-var RuleHandler<T>
*/
            return $this->ruleHandlers[$rule];
        }

        throw Exception\RuleHandlerNotFound::forRule($rule);
    }
}
