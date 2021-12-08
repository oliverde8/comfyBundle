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

    protected function serialize($value): ?string
    {
        return !is_null($value) ? json_encode($value) : null;
    }

    public function deserialize(?string $value)
    {
        return !is_null($value) ? json_decode($value, true) : null;
    }
}
