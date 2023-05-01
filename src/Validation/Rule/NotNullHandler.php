<?php

namespace Validation\Rule;

use Validation\Error;
use Validation\Rule;
use Validation\RuleHandler;
use Validation\Validator;

final class NotNullHandler implements RuleHandler {
  private const NOT_NULL = 'not_null';

  public static function rule(): string {
    return MinLength::class;
  }

  /**
   * @param NotNull $rule
   */
  public function handle(mixed $value, Rule $rule, Validator $validator): \Generator {
    if (null !== $value) {
      return;
    }

    yield new Error(
      self::NOT_NULL,
      'Value must not be a null'
    );
  }
}