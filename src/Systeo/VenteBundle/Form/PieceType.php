<?php

namespace Systeo\VenteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Systeo\VenteBundle\Form\PieceLigneType;

class PieceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $em = $options['entity_manager'];
        
        $config = $em->getRepository('SysteoConfigBundle:Config')->findOneById(1);
        
        $builder
                ->add('date', DateType::class, [
                    'label' => "Date",
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => "Date",
                        'class' => 'datepicker']])
                ->add('numero', TextType::class, [
                    "label" => 'Numéro',
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('tierName', TextType::class, [
                    "label" => 'Raison sociale / Nom Entreprise',
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('tierMf', TextType::class, [
                    "label" => 'Matricule fiscale',
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('tierAdresse', TextType::class, [
                    "label" => 'Adresse',
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('montantHt', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('montantTva', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('montantTimbre', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('montantTtc', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('tier', EntityType::class, array(
                    'class' => 'SysteoTierBundle:Tier',
                    /*'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->where('t.c = :fournisseur')
                                ->setParameter('fournisseur', true);
                    },*/
                    'choice_label' => 'name',
                    'placeholder' => '',
                    'label' => false,
                    'required' => false))
                ->add('pieceLignes', CollectionType::class, array(
                        'entry_type' => PieceLigneType::class,
                        'allow_add'    => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'entry_options'=>[
                            'config'=>$config
                        ],
                    ))
                ->add('lignesAreValid', TextType::class,[
                    "required" => false,
                ])
                ->add('montantFodec', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\VenteBundle\Entity\Piece'
        ));
        
        $resolver->setRequired('entity_manager'); 
        
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'systeo_ventebundle_piece';
    }


}
