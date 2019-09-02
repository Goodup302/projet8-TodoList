<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $roles[Role::USER] = (new Role())->setId(Role::USER)->setLabel("Utilisateur");
        $this->addReference(Role::USER, $roles[Role::USER]);

        $roles[Role::ADMIN] = (new Role())->setId(Role::ADMIN)->setLabel("Administrateur");
        $this->addReference(Role::ADMIN, $roles[Role::ADMIN]);

        $roles[Role::ANONYMOUS] = (new Role())->setId(Role::ANONYMOUS)->setLabel("Anonyme");
        $this->addReference(Role::ANONYMOUS, $roles[Role::ANONYMOUS]);

        foreach ($roles as $role) $manager->persist($role);
        $manager->flush();
    }
}
