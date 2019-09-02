<?php

namespace App\Tests\Controller;

use App\Entity\Role;
use App\Entity\Task;
use App\Tests\TestsInjections;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use TestsInjections;

    public function setUp()
    {
        $this->getClientLogged();
        $this->getClientNotLog();
    }

    /**
     * @dataProvider tasksListRoutes
     */
    public function testTaskList(string $route, string $role)
    {
        $this->getClientLogged($role);
        $this->assertCheckRoute($route);
    }
    public function tasksListRoutes() {
        return [
            ['/tasks', Role::USER],
            ['/tasks/done', Role::USER],
            ['/tasks/todo', Role::USER],
            ['/tasks', Role::ADMIN],
            ['/tasks/done', Role::ADMIN],
            ['/tasks/todo', Role::ADMIN]
        ];
    }

//    public function testcreateAction()
//    {
//        $this->assertCheckRoute('/task/create');
//    }

//    public function testeditAction()
//    {
//        $this->assertCheckRoute('/task/0/edit');
//    }
//
//    public function testtoggleTaskAction()
//    {
//        $this->assertCheckRoute('/task/0/toggle');
//    }
//
//    public function testdeleteTaskAction()
//    {
//        $this->assertCheckRoute('/task/0/delete');
//    }
//
//    public function taskProtection()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/login');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }

}
