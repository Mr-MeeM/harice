<?php

namespace Systeo\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class TeamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                    'label'=>'Désignation',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Désignation']
                ])
            ->add('description', TextareaType::class, [
                    'label'=>'Description',
                    'required' => false
                ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\UserBundle\Entity\Team'
        ));
    }
}
