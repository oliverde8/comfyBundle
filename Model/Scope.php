<?php

namespace oliverde8\ComfyBundle\Model;

/**
 * @internal This class is used only for security voters.
 */
class Scope
{
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
