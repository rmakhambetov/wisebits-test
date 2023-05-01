<?php

namespace Repository;

interface BlackListRepository {
  public function isBlacklisted(mixed $value): bool;
}