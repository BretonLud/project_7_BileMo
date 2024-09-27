<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\SecurityBundle\Security;

readonly class UserCollectionProvider implements ProviderInterface
{
    
    public function __construct(private Security $security, private UserService $userService)
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
        $currentUserCustomer = $currentUser->getCustomer();
        
        $criteria = ['customer' => $currentUserCustomer];
        
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $criteria = [];
        }
        
        return $this->userService->findBy($criteria);
    }
}