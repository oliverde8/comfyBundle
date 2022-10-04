<?php

namespace oliverde8\ComfyBundle\Security;

use oliverde8\ComfyBundle\Model\ConfigInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConfigVoter extends Voter
{
    public const ACTION_VIEW = 'view';
    public const ACTION_EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if ($subject == ConfigInterface::class) {
            return true;
        }

        if ($subject instanceof ConfigInterface) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return true;
    }
}
