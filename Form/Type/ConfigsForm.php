<?php

namespace oliverde8\ComfyBundle\Form\Type;

use oliverde8\ComfyBundle\Manager\ConfigDisplayManager;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class ConfigsForm extends AbstractType
{
    protected ConfigDisplayManager $configDisplayManager;

    /**
     * ConfigsForm constructor.
     * @param ConfigDisplayManager $configDisplayManager
     */
    public function __construct(ConfigDisplayManager $configDisplayManager)
    {
        $this->configDisplayManager = $configDisplayManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('save', SubmitType::class, ['label' => 'Save configs']);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            $scope = $data['scope'] ?? null;
            $configs = $data['configs'] ?? [];

            foreach ($configs as $config) {
                if (!is_array($config)) {
                    $this->buildFormForConfig($form, $config, $scope);
                }
            }
        });
    }

    protected function buildFormForConfig(FormInterface $form, ConfigInterface $config, ?string $scope)
    {
        $form->add(
            "value:" . $this->configDisplayManager->getConfigHtmlName($config),
            TextType::class,
            [
                'label' => $config->getName(),
                'help' => $this->getHelpHtml($config, $scope),
                'data' => $config->get($scope),
            ],
        );
        $form->add(
            "use_parent:" . $this->configDisplayManager->getConfigHtmlName($config),
            CheckboxType::class,
            [
                'label' => 'Use Parent config',
                'data' => $config->doesInherit($scope),
                'required' => false,
            ]
        );
    }

    protected function getHelpHtml(ConfigInterface $config, ?string $scope)
    {
        $helpMessage = $config->getDescription();

        if ($config->getDefaultValue() != $config->get($scope) && is_string($config->getDefaultValue())) {
            $helpMessage .= "</br>Default Value: " . $config->getDefaultValue();
        }

        return $helpMessage;
    }
}
