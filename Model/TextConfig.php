<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\Model;

use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TextConfig implements ConfigInterface
{
    public function __construct(
        protected ConfigManagerInterface $configManager,
        protected ValidatorInterface $validator,
        protected string $path,
        private string $name,
        private string $description = "",
        private int $scope = PHP_INT_MAX,
        private ?string $defaultValue = null,
        private bool $isHidden = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getScope(): int
    {
        return $this->scope;
    }

    /**
     * @inheritDoc
     */
    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function getRawValue(string $scope = null): ?string
    {
        return $this->configManager->get($this->path, $scope);
    }

    /**
     * @inheritDoc
     */
    public function set($value, string $scope = null): ConfigInterface
    {
        if (is_null($value)) {
            // Don't serialize null value when setting new value in the config manager. Null is null.
            $this->configManager->set($this->path, null, $scope);
            return $this;
        }
        $this->configManager->set($this->path, $this->serialize($value), $scope);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(&$value, string $scope = null): ConstraintViolationListInterface
    {
        return $this->validator->validate($value, $this->getValidationConstraints());
    }

    /**
     * @inheritDoc
     */
    public function get(string $scope = null)
    {
        return $this->deserialize($this->configManager->get($this->path, $scope));
    }
    /**
     * @inheritDoc
     */
    public function doesInherit(string $scope = null)
    {
        return $this->configManager->doesInhertit($this->path, $scope);
    }

    /**
     * Serialize value to save it in Database.
     *
     * @param $value
     *
     * @return string|null
     */
    protected function serialize($value): ?string
    {
        return $value;
    }

    /**
     * Deserialize value read from database.
     *
     * @param string|null $value
     *
     * @return mixed|null
     */
    protected function deserialize(?string $value)
    {
        return $value;
    }

    /**
     * Get validaiton constraints.
     *
     * @return Constraint
     */
    protected function getValidationConstraints()
    {
        return new NotBlank();
    }
}
