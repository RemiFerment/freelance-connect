<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use App\Entity\Category;
use App\Entity\Language;


class MissionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'label' => 'Catégories',
                'required' => false,
                'placeholder' => 'Toutes les catégories',
            ])

            ->add('language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'label',
                'label' => 'Langue',
                'required' => false,
                'placeholder' => 'Toutes les langues',
            ])

            ->add('minBudget', MoneyType::class, [
                'label' => 'Budget minimum',
                'required' => false,
                'currency' => 'EUR',
            ])

            ->add('maxBudget', MoneyType::class, [
                'label' => 'Budget maximum',
                'required' => false,
                'currency' => 'EUR',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method' => 'GET',
        ]);
    }
}
