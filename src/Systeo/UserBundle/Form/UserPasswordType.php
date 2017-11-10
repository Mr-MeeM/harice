<?php

namespace Systeo\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Systeo\UserBundle\Entity\User;

class UserPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $user = new User();
        
        $builder
                ->add('password',PasswordType::class,[
                    "label"=>"Mot de passe",
                    "required"=>false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Mot de passe']
                ]) 
            ;
    
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\UserBundle\Entity\User'
        ));
    }
}
