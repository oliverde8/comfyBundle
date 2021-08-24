<?php


namespace oliverde8\ComfyBundle\Resolver\Form;


use oliverde8\ComfyBundle\Model\ConfigInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

class YesNoFormProvider extends SimpleFormProvider
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
                'help' => $this->getHelpHtml($config, $scope),
                'data' => $config->get($scope),
                'choices'  => [
                    'Yes' => 1,
                    'No' => 0,
                ],
            ],
        );
    }
}