<?php

namespace Service;

use Entity\User;
use Repository\BlackListRepository;
use Repository\UniqueAbleRepository;
use Validation\Rule;
use Validation\Rule\All;
use Validation\Rule\AlphaNumeric;
use Validation\Rule\Any;
use Validation\Rule\Email;
use Validation\Rule\ForbiddenValue;
use Validation\Rule\MinLength;
use Validation\Rule\NotNull;
use Validation\Rule\ObjectProperties;
use Validation\Rule\Unique;

class UserValidationRuleFactory
{
  public function __construct(
    private User $user,
    private UniqueAbleRepository $userRepository,
    private BlackListRepository $blackListNameRepository,
    private BlackListRepository $blackListDomainRepository
  )
  {

  }
  public function createSaveRules(): Rule
  {
    return new All([
      new ObjectProperties(
        ['name' => new Any([
          new NotNull(),
          new Unique($this->user->getId(), 'name', $this->userRepository),
          new ForbiddenValue($this->blackListNameRepository),
          new AlphaNumeric(),
          new MinLength(8),
        ]),
          ['email' => new Any([
            new NotNull(),
            new Unique($this->user->getId(), 'email', $this->userRepository),
            new ForbiddenValue($this->blackListDomainRepository),
            new Email(),
          ])]
        ]),
    ]);
  }
}