<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $roles[0] = (new Role())->setName(Role::ADMIN)->setLabel("Administrateur");
        $this->addReference(Role::ADMIN, $roles[0]);
        $roles[1] = (new Role())->setName(Role::USER)->setLabel("Utilisateur");
        $this->addReference(Role::USER, $roles[1]);
        $roles[2] = (new Role())->setName(Role::ANONYMOUS)->setLabel("Anonyme");
        $this->addReference(Role::ANONYMOUS, $roles[2]);
        foreach ($roles as $role) $manager->persist($role);
        $manager->flush();
    }
}
