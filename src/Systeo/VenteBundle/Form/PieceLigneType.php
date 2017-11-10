<?php

namespace Systeo\VenteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManager;

class PieceLigneType extends AbstractType
{
  
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder->add('code', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('name', TextareaType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',]])
                ->add('quantite', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'class'=>'quantite-ligne',
                        'autocomplete' => 'off',]])
                ->add('prixHt', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'class'=>'prix-ht-ligne',
                        'autocomplete' => 'off',]])
                ->add('tauxTva',ChoiceType::class,
                        [
                            'choices'=> array_flip($options['config']->getTvaValues()),
                            'multiple'=>false,
                            'expanded'=>false,
                            "label"=>false,
                            "required"=>false,
                        ]
                )
                ->add('totalHt', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'class'=>'total-ht-ligne',
                        'autocomplete' => 'off',]]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\VenteBundle\Entity\PieceLigne'
        ));
        
         $resolver->setRequired('config');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'systeo_piece_ligne';
    }


}
