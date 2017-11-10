<?php

namespace Systeo\ReglementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Systeo\ReglementBundle\Entity\Reglement;

class ReglementAjaxType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
     
        $reglement = new Reglement();
        
        $builder->add('name', TextType::class, [
                    'label'=>'Libellé',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Nom']
                ])
                ->add('date', DateType::class, [
                    'label' => "Date",
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => "Date",
                        'class' => 'datepicker']])
                ->add('montant', TextType::class, [
                    'label'=>'Montant',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Montant']
                ])
                ->add('type',ChoiceType::class,
                        [
                            'choices'=>$reglement->getTypeValues(),
                            'multiple'=>false,
                            'expanded'=>false,
                            "label"=>'Type',
                            "required"=>false,
                            'placeholder' => ''
                        ]
                );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\ReglementBundle\Entity\Reglement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'systeo_reglementbundle_reglement';
    }


}
