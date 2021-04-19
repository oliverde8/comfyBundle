<?php

namespace oliverde8\ComfyBundle\Model;

use Symfony\Component\Validator\Constraints\PositiveOrZero;

class YesNoConfig extends TextConfig
{
    /**
     * @inheritDoc
     */
    protected function getValidationConstraints()
    {
        return new PositiveOrZero();
    }
}