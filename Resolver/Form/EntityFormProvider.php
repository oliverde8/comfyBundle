<?php

namespace oliverde8\ComfyBundle\Resolver\Form;

use oliverde8\ComfyBundle\Exception\InvalidConfigTypeForFormProvider;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Model\EntityConfig;
use oliverde8\ComfyBundle\Model\SelectConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

class EntityFormProvider extends SimpleFormProvider
{
    /**
     * @inheritdoc
     */
    public function addTypeToForm(string $name, ConfigInterface $config, FormBuilderInterface $formBuilder, string $scope)
    {
        if (!($config instanceof EntityConfig)) {
            throw new InvalidConfigTypeForFormProvider(
                sprintf("Config is expected to be of type %s but is of type %s", SelectConfig::class, get_class($config))
            );
        }

        $formBuilder->add(
            $name,
            $this->formType,
            [
                'label' => $config->getName(),
                'help' => $this->getHelpHtml($config, $scope),
                'data' => $config->get($scope),
                'class'  => $config->getEntity(),
                'choice_label' => $config->getChoiceLabel(),
            ],
        );
    }
}
