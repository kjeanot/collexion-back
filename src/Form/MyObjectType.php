<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\MyObject;
use App\Entity\MyCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MyObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('title')
            ->add('image', FileType::class, [
                'label' => 'Image de la collection',
                'mapped' => false,
            ])
            ->add('description')
            ->add('state')
            ->add('myCollections', EntityType::class, [
                'class' => MyCollection::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MyObject::class,
        ]);
    }
}
