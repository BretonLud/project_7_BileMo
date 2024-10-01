<?php

namespace App\Service;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Customer;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }
    
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null) : array
    {
        return $this->userRepository->findBy($criteria, $orderBy, $limit, $offset);
    }
    
    public function findByCustomer(?Customer $customer, array $order = [], int $limit = 30, float|int $offset = 0): Paginator
    {
        return $this->userRepository->findByCustomer($customer, $order, $limit, $offset);
    }
}
