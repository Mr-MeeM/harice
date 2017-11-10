<?php

namespace Systeo\BanqueBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BanqueCompteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', TextType::class, [
                    "label" => "Désignation",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Désignation']])
                ->add('rib', TextType::class, [
                    "label" => "RIB",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'RIB']])
                ->add('swift', TextType::class, [
                    "label" => "Swift",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Swift']])
                ->add('iban', TextType::class, [
                    "label" => "Iban",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Iban']])
                ->add('banque', TextType::class, [
                    "label" => "Banque",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Banque']])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\BanqueBundle\Entity\BanqueCompte'
        ));
    }
}
