<?php

namespace oliverde8\ComfyBundle\Model;

use Symfony\Component\Validator\Constraints\Url;

class UrlConfig extends TextConfig
{
    /**
     * @inheritDoc
     */
    protected function getValidationConstraints()
    {
        return new Url();
    }
}