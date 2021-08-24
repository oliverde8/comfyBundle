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
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConfigsForm extends AbstractType
{
    protected ConfigDisplayManager $configDisplayManager;

    /** @var FormTypeProviderInterface[] */
    protected array $formTypeProviders;

    protected $scope;

    /** @var ConfigInterface[] */
    protected $configs;

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

            $this->scope = $data['scope'] ?? null;;
            $this->configs = $data['configs'] ?? [];

            foreach ($this->configs as $config) {
                if (!is_array($config)) {
                    $this->buildFormForConfig($form, $config, $this->scope);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $hasErrors = false;

            // Validate all the configs.
            foreach ($this->configs as $config) {
                $configName = $this->configDisplayManager->getConfigHtmlName($config);

                $value = $data[$configName]['value'] ?? null;
                $useParent = $data[$configName]['use_parent'] ?? null;

                if (!is_null($value) && !$useParent) {
                    $validationResult = $config->validate($value, $this->scope);
                    if ($validationResult->count() > 0) {
                        $event->getForm()->addError(new FormError($configName . " " . $this->formatErrorMessage($validationResult)));
                        $hasErrors = true;
                    }
                }
            }

            // Apply new configs.
            if (!$hasErrors) {
                foreach ($this->configs as $config) {
                    $configName = $this->configDisplayManager->getConfigHtmlName($config);
                    $value = $data[$configName]['value'] ?? null;
                    $useParent = $data[$configName]['use_parent'] ?? null;

                    if ($useParent) {
                        $config->set(null, $this->scope);
                    } else {
                        $configData = $this->getFormProvider($config)->formatData($value);
                        $config->set($configData, $this->scope);
                    }
                }
            }
        });
    }

    protected function formatErrorMessage(ConstraintViolationListInterface $constraintViolationList)
    {
        $message = [];
        foreach ($constraintViolationList as $violation) {
            /** @var $violation ConstraintViolationInterface */
            $message[] = $violation->getMessage();
        }

        return implode(" ", $message);
    }

    protected function buildFormForConfig(FormInterface $form, ConfigInterface $config, ?string $scope)
    {
        $form->add(
            $this->configDisplayManager->getConfigHtmlName($config),
            ConfigFormType::class,
            [
                'comfy_config' => $config,
                'comfy_form_provider' => $this->getFormProvider($config),
                'comfy_scope' => $scope,
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
