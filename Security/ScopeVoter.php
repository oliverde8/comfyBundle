<?php

namespace oliverde8\ComfyBundle\Security;

use oliverde8\ComfyBundle\Model\Scope;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ScopeVoter extends Voter
{
    public const ACTION_VIEW = 'view';
    public const ACTION_EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($subject == Scope::class) {
            return true;
        }

        if ($subject instanceof Scope) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return true;
    }
}
