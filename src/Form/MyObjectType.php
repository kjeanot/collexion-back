<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\MyCollection;
use App\Entity\MyObject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('title')
            ->add('image')
            ->add('description')
            ->add('state')
            ->add('created_at')
            ->add('updated_at')
            ->add('myCollections', EntityType::class, [
                'class' => MyCollection::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
'choice_label' => 'id',
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
