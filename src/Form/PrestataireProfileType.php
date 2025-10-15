<?php

namespace App\Form;

use App\Entity\Prestataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class PrestataireProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('competences', TextareaType::class, [
                'label' => 'Compétences',
                'help' => 'Décrivez vos compétences et domaines d\'expertise',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez décrire vos compétences']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4
                ]
            ])
            ->add('biographie', TextareaType::class, [
                'label' => 'Biographie',
                'help' => 'Présentez-vous en quelques lignes',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez rédiger une biographie']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6
                ]
            ])
            ->add('tarifHoraire', MoneyType::class, [
                'label' => 'Tarif horaire (€)',
                'currency' => 'EUR',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer votre tarif horaire']),
                    new Positive(['message' => 'Le tarif doit être positif']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('statutdisponible', ChoiceType::class, [
                'label' => 'Statut de disponibilité',
                'choices' => [
                    'Disponible' => 'disponible',
                    'Occupé' => 'occupe',
                    'En vacances' => 'vacances',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner votre statut']),
                ],
                'attr' => ['class' => 'form-control']
            ])

                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestataire::class,
        ]);
    }
}