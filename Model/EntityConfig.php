<?php


namespace oliverde8\ComfyBundle\Model;


use Doctrine\ORM\EntityManagerInterface;
use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityConfig extends TextConfig
{
    private EntityManagerInterface $entityManager;

    protected string $entity;

    protected string $choiceLabel;

    public function __construct(
        ConfigManagerInterface $configManager,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        string $path,
        string $name,
        string $description = "",
        int $scope = PHP_INT_MAX,
        ?string $defaultValue = null,
        bool $isHidden = false,
        string $entity = "",
        string $choiceLabel = "id"
    ) {
        parent::__construct($configManager, $validator, $path, $name, $description, $scope, $defaultValue, $isHidden);

        $this->entityManager = $entityManager;
        $this->entity = $entity;
        $this->choiceLabel = $choiceLabel;
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
        if (is_object($value)) {
            return $value->getId();
        } else {
            return $value;
        }
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getChoiceLabel(): string
    {
        return $this->choiceLabel;
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
        if ($value) {
            return $this->entityManager->getRepository($this->entity)->find($value);
        } else {
            return null;
        }
    }
}
