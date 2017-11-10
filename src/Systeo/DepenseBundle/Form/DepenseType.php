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

class DepenseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
                    "label" => "Libellé",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Libellé']])
                ->add('remarque', TextType::class, [
                    "label" => "Remarque",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Remarque']])
                ->add('montantHt', TextType::class, [
                    "label" => "Montant HT",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Montant HT']])
                ->add('montantTva', TextType::class, [
                    "label" => "Montant TVA",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Montant TVA']])
                ->add('montantTtc', TextType::class, [
                    "label" => "Montant TTC",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Montant TTC']])
                ->add('date', DateType::class, [
                    'label' => "Date",
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => "Date",
                        'class' => 'datepicker']])
                ->add('depenseCategory', TreeType::class, [
                    'class' => 'Systeo\DepenseBundle\Entity\DepenseCategory', // tree class
                    'levelPrefix' => ' -- ',
                    "required" => false,
                    'label' => "Catégorie de dépense",
                    'orderFields' => ['root'=>'asc','lft' => 'asc'],
                    'prefixAttributeName' => 'data-level-prefix',
                    'treeLevelField' => 'lvl',
                    'placeholder'=>''
                ])
                ->add('tier', EntityType::class, array(
                    'class' => 'SysteoTierBundle:Tier',
                    /*'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->where('t.fournisseur = :fournisseur')
                                ->setParameter('fournisseur', true);
                    },*/
                    'choice_label' => 'name',
                    'placeholder' => '',
                    'label' => 'Tier / Fournisseur',
                    'attr' => [
                        'class'=>'hidden-field'
                    ],
                    'required' => false));
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
        return 'systeo_depense';
    }


}
