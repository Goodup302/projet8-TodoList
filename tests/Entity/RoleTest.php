<?php

namespace Tests\App\Entity;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Tests\TestsInjections;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleTest extends WebTestCase
{
    use TestsInjections;

    public function testAttributes() {
        $role = new Role();
        $role->setId(Role::ADMIN);
        $role->setLabel("Admin");
        $this->assertSame("Admin", $role->getLabel());
        $this->assertSame(Role::ADMIN, $role->getId());
    }

    public function testUserRelation() {
        $user = $this->getRepository(User::class)->findAll()[0];
        $role = new Role();
        $role->addUser($user);
        $this->assertSame($role->getUsers()[0], $user);
        $role->removeUser($user);
        $this->assertSame($role->getUsers()->toArray(), []);
    }
}
