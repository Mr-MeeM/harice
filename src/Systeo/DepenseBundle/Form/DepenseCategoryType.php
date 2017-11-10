<?php

namespace Systeo\DepenseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Yavin\Symfony\Form\Type\TreeType;


class DepenseCategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $builder->add('name', TextType::class, [
                    "label" => "Désignation",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Désignation']])
                ->add('parent', TreeType::class, [
                    'class' => 'Systeo\DepenseBundle\Entity\DepenseCategory', // tree class
                    'levelPrefix' => ' -- ',
                    'orderFields' => ['root'=>'asc','lft' => 'asc'],
                    'prefixAttributeName' => 'data-level-prefix',
                    'treeLevelField' => 'lvl',
                    'placeholder'=>''
                ]);
    }   
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\DepenseBundle\Entity\DepenseCategory'
        ));
        
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'systeo_depensebundle_depensecategory';
    }
    
    
    
}
