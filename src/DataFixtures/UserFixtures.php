<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    use FixturesInjection;

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail("admin@todolist.fr");
        $admin->setPassword($this->encoder->encodePassword($admin, 'admin'));
        $admin->setUsername("admin");
        $admin->setRole($this->getReference(Role::ADMIN));
        $this->setReference(self::class."0", $admin);
        $manager->persist($admin);

        $anonymous = new User();
        $anonymous->setEmail("anonymous@todolist.fr");
        $anonymous->setPassword($this->encoder->encodePassword($anonymous, uniqid(uniqid(), true)));
        $anonymous->setUsername("Anonyme");
        $anonymous->setRole($this->getReference(Role::ANONYMOUS));
        $this->setReference(self::class.Role::ANONYMOUS, $anonymous);
        $manager->persist($anonymous);

        for ($i = 1; $i < $this->iteration; $i++) {
            $user = new User();
            $user->setEmail("user.$i@todolist.fr");
            $user->setPassword($this->encoder->encodePassword($user, 'admin'));
            $user->setUsername("User $i");
            $user->setRole($this->getReference(Role::USER));
            $this->setReference(self::class.$i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [RoleFixtures::class];
    }
}
