<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use App\Tests\TestsInjections;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{

    use TestsInjections;

    private $loginButton = "Connection";

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testLogout()
    {
        $this->getClientLogged();
        $crawler = $this->clientAuth->request('GET', '/logout');
        $this->assertEquals(302, $this->clientAuth->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider loginProvider
     */
    public function testLoginForm($data, $redirect)
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton($this->loginButton)->form($data,'POST');
        $crawler = $this->client->submit($form);
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame($redirect, $crawler->filter('a')->text());
    }
    public function loginProvider() {
        /** @var User $user */
        $user = $this->getRepository(User::class)->findAll()[0];
        $password = UserFixtures::$userPassword;
        return [
            [["username"=>$user->getUsername(), "password"=>$password],"/tasks"], //testloginSuccess
            [["username"=>$user->getUsername(), "password"=>$password, "_csrf_token"=>""],"/login"], //testloginFaild
            [["username"=>"wrong_username", "password"=>"wrong_password"],"/login"], //testloginTokenError
        ];
    }


    /**
     * @dataProvider routesProvider
     */
    public function testSecurityRoutes(string $route)
    {
        $crawler = $this->client->request('GET', $route);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame("/login", $crawler->filter('a')->text());
    }
    public function routesProvider() {
        /** @var Task $task */
        $task = $this->getRepository(Task::class)->findAll()[0];
        /** @var User $user */
        $user = $this->getRepository(User::class)->findAll()[0];
        return [
            ["/"],
            ["/tasks"],
            ["/tasks/done"],
            ["/tasks/todo"],
            ["/task/create"],
            ["/task/{$task->getId()}/edit"],
            ["/task/{$task->getId()}/toggle"],
            ["/task/{$task->getId()}/delete"],
            ["/users"],
            ["/users/create"],
            ["/users/{$user->getId()}/edit"],
        ];
    }
}
