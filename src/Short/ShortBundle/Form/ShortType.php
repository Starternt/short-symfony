<?php

namespace Short\ShortBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShortType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('original_url', TextType::class, array('label' => ' '))
            ->add('desired_url', TextType::class, array('label' => ' ', 'required' => false))
            ->add('submit', SubmitType::class, array('label' => 'Получить'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'short_short_bundle_short_type';
    }
}
