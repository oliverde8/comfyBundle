<?php

namespace oliverde8\ComfyBundle\Model;

use Symfony\Component\Validator\Constraints\Json;

class JsonConfig extends TextConfig
{
    /**
     * @inheritDoc
     */
    protected function getValidationConstraints()
    {
        return new Json();
    }
}
