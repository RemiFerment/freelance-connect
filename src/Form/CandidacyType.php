<?php

namespace App\Form;

use App\Entity\Candidacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CandidacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motivation', TextareaType::class, [
                'label' => 'Motivation',
                'attr' => [
                    'placeholder' => 'Expliquez pourquoi vous êtes le meilleur candidat pour cette mission...',
                ],
            ])
            ->add('cvFilePath', FileType::class, [
                'label' => 'CV (PDF)',
                'mapped' => false,
                'attr' => [
                    'accept' => 'application/pdf'
                ]
            ])
            ->add('projectLinks', TextType::class, [
                'label' => 'Project Links',
                'attr' => [
                    'placeholder' => 'Ajoutez des liens vers vos projets pertinents...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidacy::class,
        ]);
    }
}
