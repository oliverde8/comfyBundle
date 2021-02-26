<?php

namespace oliverde8\ComfyBundle\Form\Type;

use oliverde8\ComfyBundle\Manager\ConfigDisplayManager;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Resolver\FormTypeProviderInterface;
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

    /** @var FormTypeProviderInterface[] */
    protected array $formTypeProviders;

    /**
     * ConfigsForm constructor.
     * @param ConfigDisplayManager $configDisplayManager
     */
    public function __construct(ConfigDisplayManager $configDisplayManager, array $formTypeProviders)
    {
        $this->configDisplayManager = $configDisplayManager;
        $this->formTypeProviders = $formTypeProviders;
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
        $name = "value:" . $this->configDisplayManager->getConfigHtmlName($config);
        $this->getFormProvider($config)->addTypeToForm($name, $config, $form, $scope);

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

    protected function getFormProvider(ConfigInterface $config)
    {
        foreach ($this->formTypeProviders as $formTypeProvider) {
            if ($formTypeProvider->supports($config)) {
                return $formTypeProvider;
            }
        }
    }
}
