<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserAccessVoter extends Voter
{
    public const ACCESS = 'USER_ACCESS';
    
    public function __construct(private readonly Security $security)
    {
    }
    
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, (array)self::ACCESS)
            && $subject instanceof User;
    }
    
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        $user = $subject;
       
        if (!$currentUser instanceof User) {
            return false;
        }
        
        if (!$this->security->isGranted('ROLE_CUSTOMER')) {
            return false;
        }
        
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        
        $currentUserCustomer = $currentUser->getCustomer();
        $userCustomer = $user->getCustomer();
        
        return $currentUserCustomer === $userCustomer;
    }
}