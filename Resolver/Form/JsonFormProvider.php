<?php

namespace oliverde8\ComfyBundle\Resolver\Form;

use oliverde8\ComfyBundle\Model\ConfigInterface;
use Symfony\Component\Form\FormBuilderInterface;

class JsonFormProvider extends SimpleFormProvider
{
    /**
     * @inheritdoc
     */
    public function addTypeToForm(string $name, ConfigInterface $config, FormBuilderInterface $formBuilder, string $scope)
    {
        $formBuilder->add(
            $name,
            $this->formType,
            [
                'label' => $config->getName(),
                'data' => json_encode($config->get($scope), JSON_PRETTY_PRINT),
            ],
        );
    }

    public function formatData($data)
    {
        return json_decode($data, true);
    }
}
