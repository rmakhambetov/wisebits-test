<?php

declare(strict_types=1);

namespace Validation\RuleHandlerRegistry;

use Validation\Exception;
use Validation\RuleHandler;
use Validation\RuleHandlerRegistry;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ContainerRuleHandlerRegistry implements RuleHandlerRegistry
{
    private ContainerInterface $ruleHandlers;

    public function __construct(ContainerInterface $ruleHandlers)
    {
        $this->ruleHandlers = $ruleHandlers;
    }

    /**
     * @template T of Rule
     * @psalm-param class-string<T> $rule
     * @psalm-return RuleHandler<T>
     */
    public function getRuleHandler(string $rule): RuleHandler
    {
        try {
            /** @psalm-var RuleHandler<T> */
            return $this->ruleHandlers->get($rule);
        } catch (NotFoundExceptionInterface $exception) {
            throw Exception\RuleHandlerNotFound::forRule($rule, $exception);
        }
    }
}
