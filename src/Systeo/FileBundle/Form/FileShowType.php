<?php

namespace Systeo\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FileShowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                    'label' => 'Nom',
                    'required' => false,
                    'constraints' => new NotBlank(array('message'=>'Ce champs est obligatoire!')),
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Nom']
                ])
            ->add('filecategories', EntityType::class, 
                    [
                    'class' => 'SysteoFileBundle:FileCategory',
                    'choice_label' => 'name',
                    'label' => 'Catgéories',
                    'multiple' => true,
                    'expanded'=>true
                    ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\FileBundle\Entity\File'
        ));
    }
}
