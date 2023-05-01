<?php

namespace Validation\Rule;

use Validation\Error;
use Validation\Rule;
use Validation\RuleHandler;
use Validation\Validator;

class MinLengthHandler implements RuleHandler {
  private const MIN_LEN = 'min_len';

  public static function rule(): string {
    return MinLength::class;
  }

  /**
   * @param MinLength $rule
   */
  public function handle(mixed $value, Rule $rule, Validator $validator): \Generator {
    if (null === $value) {
      return;
    }

    if ($value >= $rule->minLength) {
      return;
    }

    yield new Error(
      self::MIN_LEN,
      'Len of {value} must be greater or equals than {len}',
      [
        'value' => $value,
        'len' => $rule->minLength,
      ]
    );
  }
}