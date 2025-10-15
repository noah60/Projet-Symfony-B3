<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre adresse']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre ville']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre code postal']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre téléphone']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, GIF)',
                    ])
                ],
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}