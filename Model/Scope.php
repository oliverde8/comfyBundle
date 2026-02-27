<?php

namespace oliverde8\ComfyBundle\Model;

/**
 * @internal This class is used only for security voters.
 */
class Scope
{
    /**
     * @param string $name
     */
    public function __construct(private string $name)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
