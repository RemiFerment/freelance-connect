<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Language;
use App\Entity\Mission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options: ['label' => 'Titre'])
            ->add('description', options: ['label' => 'Description'])
            ->add('budget', options: ['label' => 'Budget'])
            ->add('deadline', options: ['label' => 'Date limite'])
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'label',
                'label' => 'Langue',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'multiple' => true,
                'label' => 'Catégories',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
