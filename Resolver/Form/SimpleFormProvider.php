<?php

namespace oliverde8\ComfyBundle\Resolver\Form;


use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Resolver\FormTypeProviderInterface;
use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class SimpleFormProvider implements FormTypeProviderInterface
{
    public function __construct(
        protected ScopeResolverInterface $scopeResolver,
        protected string $configType,
        protected string $formType
    ) {
    }

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
            ],
        );
    }

    /**
     * @inheritdoc
     */
    public function formatData($data)
    {
        // No formatting needed.
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function supports(ConfigInterface $config): bool
    {
        return $config instanceof $this->configType;
    }

    /**
     * Get html for the help field.
     *
     * @param ConfigInterface $config
     * @param string|null $scope
     * @return string
     */
    protected function getHelpHtml(ConfigInterface $config, ?string $scope)
    {
        $helpMessage = $config->getDescription();

        if (!$config->doesInherit($scope)) {
            $helpMessage .= "</br><strong>Default Value</strong>: " . $config->getDefaultValue();

            $parentScope = $this->scopeResolver->inherits($scope);
            if (!is_null($parentScope)) {
                $helpMessage .= "</br><strong>Parent Scope Value</strong>: " . json_encode($config->get($parentScope));
            }
        }

        return $helpMessage;
    }
}
