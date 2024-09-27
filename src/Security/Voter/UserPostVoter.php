<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserPostVoter extends Voter
{
    public const USER_POST = 'USER_POST';
    
    public function __construct(private readonly Security $security, private readonly RequestStack $requestStack)
    {
    }
    
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, (array)self::USER_POST)
            && $subject === null;
    }
    
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        
        if (!$currentUser instanceof User) {
            return false;
        }
        
        if (!$this->security->isGranted('ROLE_CUSTOMER'))
        {
            return false;
        }
        
        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            return true;
        }
        
        $customerId = $this->getCustomerIdFromRequest();
        
        return $currentUser->getCustomer()->getId() === $customerId;
    }
    
    private function getCustomerIdFromRequest(): ?int
    {
        // Récupère la requête courante
        $request = $this->requestStack->getCurrentRequest();
        
        if ($request === null) {
            return null;
        }
        // Suppose que vous receviez customer dans le corps de la demande de l'utilisateur comme: {'customer': '/url-to-customer/{id}'}
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['customer'])) {
            
            if (preg_match('/\/(\d+)$/', $data['customer'], $matches)) {
                return (int) $matches[1];
            }
            
            return (int) $data['customer'];
        }
        
        return null;
    }
}