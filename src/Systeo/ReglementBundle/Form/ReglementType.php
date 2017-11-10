<?php

namespace Systeo\ReglementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Systeo\ReglementBundle\Entity\Reglement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Systeo\TierBundle\Entity\Tier;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReglementType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $reglement = new Reglement();

        $builder->add('name', TextType::class, [
                    'label' => 'Libellé',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Nom']
                ])
                ->add('direction', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'hidden-field']
                ])
                ->add('tier', EntityType::class, array(
                    'class' => 'SysteoTierBundle:Tier',
                    'choice_label' => 'name',
                    'constraints'=>new NotBlank(),
                    'placeholder' => '',
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'class'=>'hidden-field'
                    ]))
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
                    'label' => 'Montant',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'Montant']
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => $reglement->getTypeValues(),
                    'multiple' => false,
                    'expanded' => false,
                    "label" => 'Type',
                    "required" => false,
                    'placeholder' => ''
                        ]
        );

        $formModifier = function (FormInterface $form, Tier $tier = null) {

            $tab1 = [
                'class' => 'SysteoVenteBundle:Piece',
                'placeholder' => '',
                'label' => 'Factures',
            ];

            $tab2 = [
                'class' => 'SysteoDepenseBundle:Depense',
                'placeholder' => '',
                'label' => 'Depenses'
            ];

            if ($tier) {
                $tab1['query_builder'] = function (EntityRepository $er) use ($tier) {
                    return $er->createQueryBuilder('a')
                                    ->andWhere('a.tier = :tier')
                                    ->setParameter('tier', $tier)
                                    ->orderBy('a.date','DESC');
                };
                
                $tab2['query_builder'] = function (EntityRepository $er) use ($tier) {
                    return $er->createQueryBuilder('a')
                                    ->andWhere('a.tier = :tier')
                                    ->setParameter('tier', $tier)
                                    ->orderBy('a.date','DESC');
                };
                
            } else {
                $tab1['choices'] = $tab2['choices'] = [];
            }



            $form->add('piece', EntityType::class, $tab1);
            $form->add('depense', EntityType::class, $tab2);
        };

        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier) {
            // this would be your entity, i.e. SportMeetup
            $data = $event->getData();

            $formModifier($event->getForm(), $data->getTier());
        }
        );

        $builder->get('tier')->addEventListener(
                FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formModifier) {
            // It's important here to fetch $event->getForm()->getData(), as
            // $event->getData() will get you the client data (that is, the ID)
            $tier = $event->getForm()->getData();

            // since we've added the listener to the child, we'll have to pass on
            // the parent to the callback functions!
            $formModifier($event->getForm()->getParent(), $tier);
        }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Systeo\ReglementBundle\Entity\Reglement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'systeo_reglementbundle_reglement';
    }

}
