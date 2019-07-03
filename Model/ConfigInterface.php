<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\Model;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ConfigInterface
{
    /**
     * Get path to the config.
     *
     * @return string
     */
    public function getPath() : string;

    /**
     * Get name of the configuration.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription() : string;

    /**
     * Get max scope depth for config..
     *
     * @return string
     */
    public function getScope() : int;

    /**
     * Do we display the config in the config windows.
     *
     * @return bool
     */
    public function isHidden(): bool;

    /**
     * Get default raw value.
     *
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * Get Raw value that is used in the storage system.
     *
     * @param string|null $scope
     *
     * @return string
     */
    public function getRawValue(string $scope = null): ?string;

    /**
     * Set value.
     *
     * @param mixed $value
     * @param string|null $scope
     *
     * @return self
     */
    public function set($value, string $scope = null): self;

    /**
     * Validates that value is usable for this config.
     *
     * @param $value
     * @param string|null $scope
     *
     * @return ConstraintViolationListInterface
     */
    public function validate(&$value, string $scope = null): ConstraintViolationListInterface;

    /**
     * Get cleaned up value that can be used by application.
     *
     * @param string|null $scope
     *
     * @return mixed
     */
    public function get(string $scope = null);

}