<?php

namespace Tests\App\Entity;

use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use App\Tests\TestsInjections;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskTest extends WebTestCase
{
    use TestsInjections;

    public function testAttributes() {
        $title = "Titre";
        $createdAt = new \DateTime();
        $content = "Lorem ipsum";

        $task = new Task();
        $task->setTitle($title);
        $task->setCreatedAt($createdAt);
        $task->setContent($content);

        $this->assertNull($task->getId());
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($createdAt, $task->getCreatedAt());
        $this->assertSame($content, $task->getContent());
        $this->assertFalse($task->isDone());
        $task->toggle(true);
        $this->assertTrue($task->isDone());

    }

    public function testUserRelation() {
        $user = $this->getRepository(User::class)->findAll()[0];
        $task = new Task();
        $this->assertNull($task->getUser());
        $task->setUser($user);
        $this->assertSame($task->getUser(), $user);
    }



    public function testPermission() {
        //users
        $defaultUser = $this->getRepository(User::class)->findOneBy(['role'=>Role::USER]);
        $adminUser = $this->getRepository(User::class)->findOneBy(['role'=>Role::ADMIN]);
        $anonymeUser = $this->getRepository(User::class)->findOneBy(['role'=>Role::ANONYMOUS]);
        //Tasks
        $userTasks = $this->getRepository(Task::class)->findOneBy(['user'=>$defaultUser]);
        $adminTasks = $this->getRepository(Task::class)->findOneBy(['user'=>$adminUser]);
        $anonymeTasks = $this->getRepository(Task::class)->findOneBy(['user'=>$anonymeUser]);



        $this->assertSame(true, $userTasks->canEditBy($defaultUser));
        $this->assertSame(false, $userTasks->canEditBy($adminUser));

        $this->assertSame(false, $adminTasks->canEditBy($defaultUser));
        $this->assertSame(true, $adminTasks->canEditBy($adminUser));

        $this->assertSame(false, $anonymeTasks->canEditBy($defaultUser));
        $this->assertSame(true, $anonymeTasks->canEditBy($adminUser));
    }

}
