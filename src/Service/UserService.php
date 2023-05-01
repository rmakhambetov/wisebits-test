<?php

namespace Service;

use Psr\Log\LoggerInterface;
use Repository\BlackListRepository;
use Repository\UniqueAbleRepository;
use Repository\UserRepositoryInterface;
use Validation\Rule\All;
use Validation\Rule\AlphaNumeric;
use Validation\Rule\Any;
use Validation\Rule\Email;
use Validation\Rule\ForbiddenValue;
use Validation\Rule\MinLength;
use Validation\Rule\NotNull;
use Validation\Rule\ObjectProperties;
use Validation\Rule\Unique;
use Validation\Validator;

final class UserService
{
  public function __construct(
    private UserRepositoryInterface $userRepository,
    private UniqueAbleRepository $uniqueAbleUserRepository,
    private BlackListRepository $blackListNameRepository,
    private BlackListRepository $blackListDomainRepository,
    private Validator $validator,
    private LoggerInterface $logger
  )
  {
  }

  /**
   * @return \Entity\User[]
   */
  public function findAll(): array
  {
    return $this->userRepository->findAll();
  }

  public function findById(string $id): ?\Entity\User
  {
    return $this->userRepository->findById($id);
  }

  public function save(\Entity\User $user): bool
  {
    $errors = $this->validator->validate(
      $user,
      new All([
        new ObjectProperties(
          ['name' => new Any([
            new NotNull(),
            new Unique($user->getId(), 'name', $this->uniqueAbleUserRepository),
            new ForbiddenValue($this->blackListNameRepository),
            new AlphaNumeric(),
            new MinLength(8),
          ]),
          ['email' => new Any([
            new NotNull(),
            new Unique($user->getId(), 'email', $this->uniqueAbleUserRepository),
            new ForbiddenValue($this->blackListDomainRepository),
            new Email(),
          ])]
        ]),
    ]));

    if (!empty($errors)) {
      throw new ValidationException($errors);
    }

    $result = $user->getId()
      ? $this->userRepository->update($user)
      : $this->userRepository->create($user);

    if (!$result) {
      throw new \RuntimeException('Cannot update/create user');
    }

    $this->logger->info(
      'User has been created/updated',
      (array)$user
    );

    return $result;
  }
}