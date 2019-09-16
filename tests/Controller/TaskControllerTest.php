<?php

namespace App\Tests\Controller;

use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use App\Tests\TestsInjections;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class TaskControllerTest extends WebTestCase
{
    use TestsInjections;

    public function setUp()
    {
        $this->getClientLogged();
        $this->getClientNotLog();
        $this->setUpKernel();
    }

    /**
     * @dataProvider tasksListRoutes
     */
    public function testTaskRoutes(string $route, string $role)
    {
        $this->getClientLogged($role);
        $this->assertCheckRoute($route);
    }
    public function tasksListRoutes() {
        return [
            ['/tasks', Role::USER],
            ['/tasks/done', Role::USER],
            ['/tasks/todo', Role::USER],
            ['/task/create', Role::USER],
            ['/tasks', Role::ADMIN],
            ['/tasks/done', Role::ADMIN],
            ['/tasks/todo', Role::ADMIN],
            ['/task/create', Role::ADMIN],
        ];
    }


    public function testCreateAction()
    {
        //todo get id of insert
        $this->submitTaskForm("/task/create", "Ajouter", [
            'task[title]' => 'created title',
            'task[content]' => 'first content'
        ]);
    }

    /**
     * @depends testCreateAction
     */
    public function testEditAction()
    {
        $this->setUpKernel();
        $queryBuilder = $this->getRepository(Task::class)->findOneBy(['user' => $this->sessionUser['admin']]);

        $res = $queryBuilder->getQuery()->getSingleResult();
        $id = $res['id'] + 1;
        $this->submitTaskForm("/task/$id/edit", "Modifier", [
            'task[title]' => 'edited title',
            'task[content]' => 'content edited'
        ]);
    }

    /**
     * @depends testEditAction
     */
    public function testToggleAction()
    {
        //$this->callTaskRoute("/task/$id/toggle");
    }

    /**
     * @depends testToggleAction
     */
    public function testDeleteAction()
    {
        //$this->callTaskRoute("/task/$id/delete");
    }

    public function submitTaskForm(string $route, string $button = null, array $data = [])
    {
        $this->clientAuth->request('GET', $route);
        $crawler = $this->clientAuth->submitForm($button, $data);
        $this->CrudSuccess($crawler);
    }

    public function callTaskRoute(string $route)
    {
        $crawler = $this->clientAuth->request('POST', $route);
        $this->CrudSuccess($crawler);
    }

    public function CrudSuccess(Crawler $crawler)
    {
        print_r($crawler);
        //Test Redidect to task list
        $this->assertEquals(302, $this->clientAuth->getResponse()->getStatusCode());
        $this->assertContains('tasks', $crawler->filter('a')->text());
        //Test Alert message
        $crawler = $this->clientAuth->request('GET', "/tasks");
        $this->assertNotEmpty($crawler->filter('div.alert-success')->text());
    }

}
