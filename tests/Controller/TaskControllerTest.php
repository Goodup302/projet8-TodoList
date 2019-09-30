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

    public $tasks =[];

    public function setUp()
    {
        $this->getClientLogged();
        $this->getClientNotLog();
        $this->setUpKernel();
    }

    public function testindexAction()
    {
        $this->assertCheckRoute('/', 302, "/tasks");
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
        $this->submitTaskForm("/task/create", "Ajouter", [
            'task[title]' => 'created title',
            'task[content]' => 'first content'
        ]);
    }



    public function testEditSuccess()
    {
        $task = $this->getRepository(Task::class)->findOneBy(['user' => $this->getUserByName('admin')->getId()]);
        $this->submitTaskForm("/task/{$task->getId()}/edit", "Modifier", [
            'task[title]' => 'edited title',
            'task[content]' => 'content edited'
        ]);
    }
    public function testEditFailed()
    {
        $this->getClientLogged(Role::USER);
        $task = $this->getRepository(Task::class)->findOneBy(['user' => $this->getUserByName('admin')->getId()]);
        $crawler = $this->clientAuth->request('GET', "/task/{$task->getId()}/edit");
        $this->CrudFailed($crawler);
    }



    public function testToggleSuccess()
    {
        $username = $this->sessionUser['admin']['PHP_AUTH_USER'];
        $task = $this->getRepository(Task::class)->findOneBy(['user' => $this->getUserByName($username)->getId()]);
        $crawler = $this->clientAuth->request('GET', "/task/{$task->getId()}/toggle");
        $this->CrudSuccess($crawler);
    }
    public function testToggleFailed()
    {
        $this->getClientLogged(Role::USER);
        $username = $this->sessionUser['admin']['PHP_AUTH_USER'];
        $task = $this->getRepository(Task::class)->findOneBy(['user' => $this->getUserByName($username)->getId()]);
        $crawler = $this->clientAuth->request('GET', "/task/{$task->getId()}/toggle");
        $this->CrudFailed($crawler);
    }



    public function testDeleteSuccess()
    {
        $task = $this->getRepository(Task::class)->findOneBy(['user' => $this->getUserByName('admin')->getId()]);
        $crawler = $this->clientAuth->request('GET', "/task/{$task->getId()}/delete");
        $this->CrudSuccess($crawler);
    }
    public function testDeleteFailed()
    {
        $this->getClientLogged(Role::USER);
        $username = $this->sessionUser['admin']['PHP_AUTH_USER'];
        $task = $this->getRepository(Task::class)->findOneBy(['user' => $this->getUserByName($username)->getId()]);
        $crawler = $this->clientAuth->request('GET', "/task/{$task->getId()}/delete");
        $this->CrudFailed($crawler);
    }



    public function submitTaskForm(string $route, string $button, array $data = [])
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
        //Test Redidect to task list
        $this->assertEquals(302, $this->clientAuth->getResponse()->getStatusCode());
        $this->assertContains('tasks', $crawler->filter('a')->text());
        //Test Alert message
        $crawler = $this->clientAuth->request('GET', "/tasks");
        $this->assertNotEmpty($crawler->filter('div.alert-success')->text());
    }

    public function CrudFailed(Crawler $crawler)
    {
        //Test Redidect to task list
        $this->assertEquals(302, $this->clientAuth->getResponse()->getStatusCode());
        $this->assertContains('tasks', $crawler->filter('a')->text());
        //Test Alert message
        $crawler = $this->clientAuth->request('GET', "/tasks");
        $this->assertNotEmpty($crawler->filter('div.alert-danger')->text());
    }
}
