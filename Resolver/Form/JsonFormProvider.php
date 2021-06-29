<?php

namespace oliverde8\ComfyBundle\Resolver\Form;

use oliverde8\ComfyBundle\Model\ConfigInterface;
use Symfony\Component\Form\FormInterface;

class JsonFormProvider extends SimpleFormProvider
{
    /**
     * @inheritdoc
     */
    public function addTypeToForm(string $name, ConfigInterface $config, FormInterface $formBuilder, string $scope)
    {
        $formBuilder->add(
            $name,
            $this->formType,
            [
                'label' => $config->getName(),
                'data' => $config->get($scope),
            ],
        );
    }
}
