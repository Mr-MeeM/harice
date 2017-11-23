<?php

namespace Systeo\ConfigBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('logo', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'class'=>'hidden-field',
                        'autocomplete' => 'off']])
                ->add('companyName', TextType::class, [
                    "label" => "Nom de la société",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Nom de la société']])
                ->add('droitTimbre', TextType::class, [
                    "label" => "Droit de timbre",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => '0.500']])
                ->add('tauxTva', TextType::class, [
                    "label" => "Taux TVA",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => '0;6;18']])
                
                ->add('tel', TextType::class, [
                    "label" => "Téléphone",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('fax', TextType::class, [
                    "label" => "Fax",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('email', TextType::class, [
                    "label" => "Email",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('web', TextType::class, [
                    "label" => "Site web",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                
                ->add('mf', TextType::class, [
                    "label" => "Matricule fiscale",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('rc', TextType::class, [
                    "label" => "Registre de commerce",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('cd', TextType::class, [
                    "label" => "Code en douane",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('adresse', TextType::class, [
                    "label" => "Adresse",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                
                ->add('banque', TextType::class, [
                    "label" => "Banque",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('rib', TextType::class, [
                    "label" => "RIB",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('swift', TextType::class, [
                    "label" => "SWIFT",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('iban', TextType::class, [
                    "label" => "IBAN",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                
                ->add('couleur1', TextType::class, [
                    "label" => "Couelur 1",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off']])
                ->add('fodec', TextType::class, [
                    "label" => "Fodec",
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'Fodec',
                        'placeholder' => 'Taux Fodec']])
                
                
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\ConfigBundle\Entity\Config'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'systeo_configbundle_config';
    }


}
