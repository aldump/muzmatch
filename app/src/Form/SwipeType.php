<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Swipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwipeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'user',
            EntityType::class,
            [
                'entity' => User::class
            ],
        );
        $builder->add(
            'target',
            EntityType::class,
            [
                'entity' => User::class
            ],
        );
        $builder->add('status', TextType::class);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Swipe::class,
            ],
        );
    }
}
