<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }
    
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
    {
        return $this->userRepository->findBy($criteria, $orderBy, $limit, $offset);
    }
}