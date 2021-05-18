<?php


namespace oliverde8\ComfyBundle\Resolver\Form;


use oliverde8\ComfyBundle\Exception\InvalidConfigTypeForFormProvider;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Model\SelectConfig;
use Symfony\Component\Form\FormInterface;

class SelectFormProvider extends SimpleFormProvider
{
    /**
     * @inheritdoc
     */
    public function addTypeToForm(string $name, ConfigInterface $config, FormInterface $formBuilder, string $scope)
    {
        if (!($config instanceof SelectConfig)) {
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
                'choices'  => $config->getOptions(),
                'multiple' => $config->isAllowMultiple(),
            ],
        );
    }
}
