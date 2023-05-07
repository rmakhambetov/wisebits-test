<?php

namespace App\Service;

use App\Validation\Error;

final class ValidationException extends \DomainException
{
  /**
   * @param Error[] $errors
   */
    public function __construct(private array $errors = [])
    {
        $msg = implode(
            "\n",
            array_map(fn(Error $error) => "{$error->code}: {$error->message}", $this->errors)
        );
        parent::__construct($msg);
    }
}
