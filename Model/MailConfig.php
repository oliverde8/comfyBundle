<?php

namespace oliverde8\ComfyBundle\Model;

use Symfony\Component\Validator\Constraints\Email;

class MailConfig extends TextConfig
{
    /**
     * @inheritDoc
     */
    protected function getValidationConstraints()
    {
        return new Email();
    }
}