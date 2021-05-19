<?php


namespace oliverde8\ComfyBundle\Model;


use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SelectConfig extends TextConfig
{
    /** @var string[] */
    protected $options = [];

    /** @var boolean */
    protected $allowMultiple;

    public function __construct(
        ConfigManagerInterface $configManager,
        ValidatorInterface $validator,
        string $path,
        string $name,
        string $description = "",
        int $scope = PHP_INT_MAX,
        ?string $defaultValue = null,
        bool $isHidden = false,
        $options = [],
        $allowMultiple = false
    ) {
        parent::__construct($configManager, $validator, $path, $name, $description, $scope, $defaultValue, $isHidden);

        $this->options = $options;
        $this->allowMultiple = $allowMultiple;
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
        return json_encode($value);
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
        return json_decode($value);
    }

    /**
     * @return string[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return bool
     */
    public function isAllowMultiple()
    {
        return $this->allowMultiple;
    }


}
