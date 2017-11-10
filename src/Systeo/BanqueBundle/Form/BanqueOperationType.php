<?php

namespace Systeo\BanqueBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BanqueOperationType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, [
                    "label" => "Libellé",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Libellé']])
                ->add('date', DateType::class, [
                    'label' => "Date",
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => "Date",
                        'class' => 'datepicker']])
                ->add('dateValeur', DateType::class, [
                    'label' => "Date Valeur",
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => "Date Valeur",
                        'class' => 'datepicker']])
                ->add('debit', TextType::class, [
                    "label" => "Débit",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Débit']])
                ->add('credit', TextType::class, [
                    "label" => "Crédit",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Crédit']])
                ->add('banqueCompte', EntityType::class, [
                    'class' => 'SysteoBanqueBundle:BanqueCompte',
                    'choice_label' => 'name',
                    'label' => 'Compte',
                    'required' => false,])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\BanqueBundle\Entity\BanqueOperation'
        ));
    }

}
