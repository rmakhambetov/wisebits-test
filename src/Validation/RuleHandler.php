<?php

declare(strict_types=1);

namespace Validation;

/**
 * @template R of Rule
 */
interface RuleHandler
{
  /**
   * @psalm-return class-string<R>
   */
  public static function rule(): string;

  /**
   * @psalm-param R $rule
   * @psalm-return \Generator<int, Error>
   */
  public function handle(mixed $value, Rule $rule, Validator $validator): \Generator;
}