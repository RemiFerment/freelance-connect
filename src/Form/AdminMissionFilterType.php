<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdminMissionFilterType extends AbstractType
{
    public function __construct(private UserRepository $userRepository) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => User::class,
                'label' => 'Clients',
                'choices' => $this->userRepository->findClients(),
                'choice_label' => function (User $user) {
                    return $user->getFirstname().' '.$user->getLastname();
                },
                'placeholder' => 'Tous les clients',
                'required' => false,
            ])

            ->add('freelance', EntityType::class, [
                'class' => User::class,
                'label' => 'Freelances',
                'choices' => $this->userRepository->findFreelances(),
                'choice_label' => function (User $user) {
                    return $user->getFirstname().' '.$user->getLastname();
                },
                'placeholder' => 'Tous les freelances',
                'required' => false,
            ])

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'label' => 'Catégories',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'placeholder' => 'Toutes les catégories',
            ])

            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Créée depuis',
            ])

            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Créée jusqu\'au',
            ])

            ->add('minBudget', IntegerType::class, [
                'required' => false,
                'label' => 'Budget minimum',
            ])

            ->add('maxBudget', IntegerType::class, [
                'required' => false,
                'label' => 'Budget maximum',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
