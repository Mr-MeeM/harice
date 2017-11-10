<?php

namespace Systeo\TierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Systeo\TierBundle\Entity\Tier;

class TierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tier = new Tier();
        
        $builder->add('rs', TextType::class, [
                    "label" => "Raison sociale",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Raison sociale']])
                ->add('firstName', TextType::class, [
                    "label" => "Prénom",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Prénom']])
                ->add('lastName', TextType::class, [
                    "label" => "Nom",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Nom']])
                ->add('tel1', TextType::class, [
                    "label" => "Téléphone 1",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Téléphone 1']])
                ->add('tel2', TextType::class, [
                    "label" => "Téléphone 2",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Téléphone 2']])
                ->add('fax1', TextType::class, [
                    "label" => "Fax 1",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Fax 1']])
                ->add('email1', TextType::class, [
                    "label" => "Email 1",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Email 1']])
                ->add('email2', TextType::class, [
                    "label" => "Email 2",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Email 2']])
                
                ->add('rueNumero1', TextType::class, [
                    "label" => "Rue/Numéro",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Rue/Numéro']])
                ->add('cp1', TextType::class, [
                    "label" => "Code postal",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Code postal']])
                ->add('ville1', TextType::class, [
                    "label" => "Ville",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Ville']])
                ->add('pays1', TextType::class, [
                    "label" => "Pays",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Pays']])
                
                ->add('fournisseur', CheckboxType::class, [
                    'label' => 'Fournisseur',
                    'required' => false
                ])
                ->add('client', CheckboxType::class, [
                    'label' => 'Client',
                    'required' => false
                ])
                ->add('employe', CheckboxType::class, [
                    'label' => 'Employé',
                    'required' => false
                ])
                ->add('mf', TextType::class, [
                    "label" => "Matricule fiscale",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Matricule fiscale']])
                ->add('rc', TextType::class, [
                    "label" => "Registre de commerce",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Registre de commerce']])
                ->add('type',ChoiceType::class,
                        [
                            'choices'=> array_flip($tier->getTypeValues()),
                            'multiple'=>false,
                            'expanded'=>false,
                            "label"=>'Morale/Physique',
                            "required"=>false,
                            'placeholder'=>false
                        ]
                );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\TierBundle\Entity\Tier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'systeo_tier';
    }


}
