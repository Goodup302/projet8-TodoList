<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $roles[0] = (new Role())->setName(Role::ADMIN);
        $this->addReference(self::class.Role::ADMIN, $roles[0]);
        $roles[1] = (new Role())->setName(Role::USER);
        $this->addReference(self::class.Role::USER, $roles[1]);
        foreach ($roles as $role) $manager->persist($role);
        $manager->flush();
    }
}
