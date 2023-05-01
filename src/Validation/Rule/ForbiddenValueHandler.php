<?php

namespace Validation\Rule;

use Validation\Error;
use Validation\Rule;
use Validation\RuleHandler;
use Validation\Validator;

final class ForbiddenValueHandler implements RuleHandler
{
  private const FORBIDDEN_VALUE = 'forbidden_value';

  public static function rule(): string
  {
    return ForbiddenValue::class;
  }

  /**
   * @param ForbiddenValue $rule
   */
  public function handle(mixed $value, Rule $rule, Validator $validator): \Generator {
    if (null === $value) {
      return;
    }

    if ($rule->getRepository()->isBlacklisted($value)) {
      return;
    }

    yield new Error(
      self::FORBIDDEN_VALUE,
      'Value {value} is forbidden',
      ['value' => $value]
    );
  }
}