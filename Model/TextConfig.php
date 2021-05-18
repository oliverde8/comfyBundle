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
    /** @var ConfigManagerInterface */
    protected $confgManager;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var string */
    protected $path;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var int */
    private $scope;

    /** @var bool */
    private $isHidden;

    /** @var string|null */
    private $defaultValue;

    /**
     * AbstractConfig constructor.
     * @param ConfigManagerInterface $configManager
     * @param ValidatorInterface $validator
     * @param string $path
     * @param string $name
     * @param string $description
     * @param int $scope
     * @param string|null $defaultValue
     * @param bool $isHidden
     */
    public function __construct(
        ConfigManagerInterface $configManager,
        ValidatorInterface $validator,
        string $path,
        string $name,
        string $description = "",
        int $scope = PHP_INT_MAX,
        ?string $defaultValue = null,
        bool $isHidden = false
    ) {
        $this->confgManager = $configManager;
        $this->validator = $validator;
        $this->path = $path;
        $this->name = $name;
        $this->description = $description;
        $this->scope = $scope;
        $this->isHidden = $isHidden;
        $this->defaultValue = $defaultValue;
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
        $this->confgManager->get($this->path, $scope);
    }

    /**
     * @inheritDoc
     */
    public function set($value, string $scope = null): ConfigInterface
    {
        if (is_null($value)) {
            // Don't serialize null value when setting new value in the config manager. Null is null.
            $this->confgManager->set($this->path, null, $scope);
            return $this;
        }
        $this->confgManager->set($this->path, $this->serialize($value), $scope);

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
        return $this->deserialize($this->confgManager->get($this->path, $scope));
    }
    /**
     * @inheritDoc
     */
    public function doesInherit(string $scope = null)
    {
        return $this->confgManager->doesInhertit($this->path, $scope);
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
