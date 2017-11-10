<?php

namespace Systeo\DepenseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Yavin\Symfony\Form\Type\TreeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class DepenseSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('depenseCategory', TreeType::class, [
                    'class' => 'Systeo\DepenseBundle\Entity\DepenseCategory', // tree class
                    'levelPrefix' => ' -- ',
                    'label' => "Catégorie de dépense",
                    'orderFields' => ['root'=>'asc','lft' => 'asc'],
                    'prefixAttributeName' => 'data-level-prefix',
                    'treeLevelField' => 'lvl',
                    'placeholder'=>'',
                ]);
               
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\DepenseBundle\Entity\Depense'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'depense_search';
    }


}
