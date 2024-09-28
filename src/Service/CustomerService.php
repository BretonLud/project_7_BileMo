<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;

readonly class CustomerService
{
    public function __construct(private CustomerRepository $customerRepository)
    {
    }
    
    public function find(int $id): ?Customer
    {
        return $this->customerRepository->find($id);
    }
}