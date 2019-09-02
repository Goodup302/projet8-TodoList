<?php

namespace Tests\App\Entity;

use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use App\Tests\TestsInjections;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    use TestsInjections;

    public function testAttributes() {
        $username = "username";
        $password = 'password';
        $email = "test@test.fr";

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);

        $this->assertNull($user->getId());
        $this->assertSame($username, $user->getUsername());
        $this->assertSame($password, $user->getPassword());
        $this->assertSame($email, $user->getEmail());

    }

    public function testRoleRelation() {
        $role = $this->getRepository(Role::class)->findAll()[0];
        $user = new User();

        $this->assertNull($user->getRole());
        $this->assertSame($user->getRoles(), []);
        $this->assertNull($user->getRoleName());

        $user->setRole($role);

        $this->assertSame($user->getRole(), $role);
        $this->assertSame($user->getRoles(), [$role->getId()]);
        $this->assertSame($user->getRoleName(), $role->getId());
    }

    public function testTaskRelation() {
        $task = $this->getRepository(Task::class)->findAll()[0];
        $user = new User();

        $user->addTask($task);
        $this->assertSame($user->getTasks()[0], $task);
        $user->removeTask($task);
        $this->assertSame($user->getTasks()->toArray(), []);
    }
}
