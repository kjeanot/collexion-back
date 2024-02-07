<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\MyCollection;
use App\Entity\MyObject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // Créer un utilisateur admin
        $userAdmin = new User();
        $userAdmin->setEmail('admin@admin.com');
        $userAdmin->setNickname('admin');
        $userAdmin->setPassword(password_hash('admin', PASSWORD_BCRYPT));
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);

        // Créer 10 categories parents
        for($i = 0; $i < 10; $i++){
            $parentCategory = new Category();
            $parentCategory->setName('Category parent ' . $i);
            $manager->persist($parentCategory);
            // Créer 3 categories enfants pour chaque catégorie parents
            for($j = 0; $j < 3; $j++){
                $enfantCategory = new Category();
                $enfantCategory->setName('Category enfant ' . $j);
                $manager->persist($enfantCategory);
                // Assigne une catégorie enfant à chaque catégorie parents
                $parentCategory->addCategory($enfantCategory);
                $manager->persist($parentCategory);
            }
        }
        
        // Créer 10 utilisateurs users
        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setEmail('user' . $i . '@user.com');
            $user->setNickname('user ' . $i);
            $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        // Créer 1 collection pour chaques utilisateurs users
            $collection = new MyCollection();
            $collection->setUser($user);
            $collection->setName('Ma premiere collection ');
            $collection->setImage('https://via.placeholder.com/150');
            $collection->setDescription( $i .'test' );
            $manager->persist($collection);

            $manager->persist($user);

            // Créer 3 objets pour chaques collections
            for($j = 0; $j < 3; $j++){
                $object = new MyObject();
                $object->setName('Object ' . $j);
                $object->setTitle('Title ' . $j);
                $object->setImage('https://via.placeholder.com/150');
                $object->setDescription('Description ' . $j);
                $object->setState('State ' . $j);
                $object->setCategory($parentCategory);
                $manager->persist($object);
                $collection->addMyobject($object);
                $manager->persist($collection);
            }
            // On flush en ammont pour creer les collections et les objet afin de les assigner aux favoris des utilisateurs et de créer un commentaire sur chaque objet
            $manager->flush();
        }   

        // Attribue de manière aléatoire 2 collections favorites à chaque utilisateur
        // Liste de tous les utilisateurs que l' on a creer en ammont
        $users = $manager->getRepository(User::class)->findAll();
        // Liste de tous les collections que l' on a creer en ammont
        $collections = $manager->getRepository(MyCollection::class)->findAll();
        // On boucle sur les utilisateurs que l' on a creer en ammont
        foreach ($users as $user) {
            // On récupère 2 index aléatoires dans le tableau des users
            $randomCollections = array_rand($collections, 2);
            // On boucle sur les index aléatoires
            foreach ($randomCollections as $index) {
                // On ajoute les collections correspondant à l'index qui a etait choisi aléatoirement à la liste des collections favorites de l' utilisateur
                $user->addMyFavoriteCollection($collections[$index]);
            }
            $manager->persist($user);
        }

        // Créer un commentaire d' un user pour chaque objet de manière aléatoire
        // Liste de tous les objets que l' on a creer en ammont
        $objects = $manager->getRepository(MyObject::class)->findAll();
        // On boucle sur les objets que l' on a creer en ammont
        foreach ($objects as $object) {
            // On instancie (créer) un commentaire
            $comment = new Comment();
            // On attribue un utilisateur aléatoire à chaque commentaire creer
            $comment->setUser($users[array_rand($users)]);
            $comment->setContent('Comment ');
            $comment->setMyObject($object);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
