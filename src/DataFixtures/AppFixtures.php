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
        
        // Créer 10 utilisateur user
        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setEmail('user' . $i . '@user.com');
            $user->setNickname('user ' . $i);
            $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        // Créer 1 collection pour chaque utilisateur user
            $collection = new MyCollection();
            $collection->setUser($user);
            $collection->setName('Ma premiere collection ');
            $collection->setImage('https://via.placeholder.com/150');
            $collection->setDescription( $i .'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum' );
            $collectionList[] = $collection;
            $manager->persist($collection);

            $user->addMyFavoriteCollection($collection[rand(0,1)]);
            $manager->persist($user);

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
        }

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment ');
        $comment->setMyObject($object);
        $manager->persist($comment);

        $manager->flush();
    }
}
