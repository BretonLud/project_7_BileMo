<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Service\CustomerService;
use App\Service\UserService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class UserCollectionProvider implements ProviderInterface
{
    
    public function __construct(
        private Security $security,
        private UserService $userService,
        private RequestStack $requestStack,
        private CustomerService $customerService,
    )
    {
    }
    
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
    {
        // Récupère l'utilisateur actuellement connecté
        $currentUser = $this->security->getUser();
        
        if (!$currentUser instanceof User) {
            return [];
        }
        // Récupère le Customer de l'utilisateur actuellement connecté
        $customer = $currentUser->getCustomer();
        $request = $this->requestStack->getCurrentRequest();
        // Paramètres de pagination
        $limit = $request->query->getInt('limit', 30);
        $page = $request->query->getInt('page', 1);
        $offset = ($page - 1) * $limit;
 
        if ($this->security->isGranted('ROLE_ADMIN') && $request->query->getInt('customer')) {
            $customer = $this->customerService->find($request->query->getInt('customer'));
        } elseif ($this->security->isGranted('ROLE_ADMIN'))
        {
            $customer = null;
        }
        
        return $this->userService->findByCustomer($customer,[],$limit, $offset);
    }
}
