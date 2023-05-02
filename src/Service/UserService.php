<?php

namespace Service;

use Psr\Log\LoggerInterface;
use Repository\UserRepositoryInterface;
use Validation\Validator;

final class UserService
{
  public function __construct(
    private UserRepositoryInterface $userRepository,
    private UserValidationRuleFactory $validationRuleFactory,
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
    $errors = $this->validator->validate($user, $this->validationRuleFactory->createSaveRules());

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