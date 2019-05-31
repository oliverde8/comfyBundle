<?php
/**
 * @author    Wide Agency Dev Team
 * @category  kit-v2-symfony
 */

namespace oliverde8\ComfyBundle\Storage;


use Doctrine\ORM\EntityManagerInterface;
use oliverde8\ComfyBundle\Entity\ConfigValue;

class DoctrineEntityStorage implements StorageInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ConfigValue[][] */
    protected $entities = [];

    /**
     * DoctrineEntityStorage constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function save(string $configPath, string $scope, ?string $value)
    {
        // Be sure scope is loaded.
        $this->load([$scope]);

        if (!isset($this->entities[$scope][$configPath])) {
            $this->entities[$scope][$configPath] = new ConfigValue();
            $this->entities[$scope][$configPath]->setPath($configPath)
                ->setScope($scope);
        }


        $this->entities[$scope][$configPath]->setValue($value);

        $this->entityManager->persist($this->entities[$scope][$configPath]);
    }

    public function load(array $scopes): array
    {
        $qb = $this->entityManager->getRepository(ConfigValue::class)->createQueryBuilder("c");
        $qb->where($qb->expr()->in('c.scope', $scopes));

        /** @var ConfigValue[] $result */
        $result = $qb->getQuery()->getResult();

        $groupedConfigValues = [];
        foreach ($result as $config){
            $groupedConfigValues[$config->getScope()][$config->getPath()] = $config->getValue();
            $this->entities[$config->getScope()][$config->getPath()] = $config;
        }

        return $groupedConfigValues;
    }

    public function __destruct()
    {
        $this->entityManager->flush();
    }
}
