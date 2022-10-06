<?php

namespace oliverde8\ComfyBundle\Form\Type;

use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Resolver\FormTypeProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ConfigInterface $config */
        $config = $options['comfy_config'];
        /** @var FormTypeProviderInterface $formProvider */
        $formProvider = $options['comfy_form_provider'];
        /** @var string $scope */
        $scope = $options['comfy_scope'];
        /** @var boolean $scope */
        $disabled = $options['disabled'];

        $formProvider->addTypeToForm('value', $config, $builder, $scope);

        $builder->add(
            "use_parent",
            CheckboxType::class,
            [
                'label' => 'Use Parent config',
                'data' => $config->doesInherit($scope),
                'required' => false,
            ]
        );

        if ($disabled) {
            foreach ($builder->all() as $formBuilder) {
                $formBuilder->setDisabled(true);
                $formBuilder->setAttribute('disabled', 'true');
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'comfy_config' => null,
            'comfy_form_provider' => null,
            'comfy_scope' => null,
            'disabled' => false,
        ]);
    }
}
