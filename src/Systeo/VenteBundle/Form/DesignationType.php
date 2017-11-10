<?php

namespace Systeo\VenteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class DesignationType extends AbstractType
{
  
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder->add('designation',CKEditorType::class,[
                    "label"=>false,
                    'config_name'       => 'my_config_2',
                    "required"=>false,
                    ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
     
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'systeo_designation';
    }


}
