<?php

namespace App\Form;

use App\Entity\MyCollection;
use App\Entity\MyObject;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('image')
            ->add('description')
            ->add('is_active')
            ->add('user', EntityType::class, [
                'label' => 'User assignment',
                'class' => User::class,
                'choice_label' => 'nickname',
            ])
            ->add('myobjects', EntityType::class, [
                'label' => 'Objects assignments',
                'class' => MyObject::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('rating')
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('myobjects', EntityType::class, [
                'class' => MyObject::class,
'choice_label' => 'id',
'multiple' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MyCollection::class,
        ]);
    }
}
