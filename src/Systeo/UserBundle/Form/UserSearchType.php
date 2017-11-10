<?php

namespace Systeo\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserSearchType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('search', TextType::class, [
                    "label" => false,
                    "required" => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Prénom & Nom / Identifiant']])
                ->add('active', ChoiceType::class, [
                    'choices' => ['Oui' => 1, 'Non' => 0],
                    'multiple' => false,
                    'expanded' => false,
                    "label" => false,
                    "required" => false,
                    'placeholder' => 'Etat Actif']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\UserBundle\Entity\User'
        ));
    }

}
