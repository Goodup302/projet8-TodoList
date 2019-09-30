<?php

namespace App\Tests\Controller;

use App\DataFixtures\RoleFixtures;
use App\DataFixtures\TaskFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Role;
use App\Entity\User;
use App\Tests\TestsInjections;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserControllerTest extends WebTestCase
{
    use TestsInjections;

    public function setUp()
    {
        $this->getClientLogged(Role::ADMIN);
        $this->getClientNotLog();
    }

    /**
     * @dataProvider routesProvider
     */
    public function testSecurityRoutes(string $route)
    {
        $this->getClientLogged(Role::USER);
        try {
            $this->clientAuth->request('GET', $route);
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
    public function routesProvider() {
        /** @var User $user */
        $user = $this->getRepository(User::class)->findAll()[0];
        return [
            ["/users"],
            ["/users/create"],
            ["/users/{$user->getId()}/edit"],
        ];
    }

    public function testListUser()
    {
        $this->assertSafeRoute('/users');
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser()
    {
        $this->clientAuth->request('GET', "/users/create");
        $crawler = $this->clientAuth->submitForm("Ajouter", [
            "user[username]" => "User".uniqid(),
            "user[password][first]" => "admin",
            "user[password][second]" => "admin",
            "user[email]" => "test".uniqid()."@test.test",
            "user[role]" => Role::USER,
        ]);
        //Test Redidect to task list
        $this->assertEquals(302, $this->clientAuth->getResponse()->getStatusCode());
        $this->assertContains('users', $crawler->filter('a')->text());
        //Test Alert message
        $crawler = $this->clientAuth->request('GET', "/users");
        $this->assertNotEmpty($crawler->filter('div.alert-success')->text());
    }

    public function testEditUser()
    {
        $user = $this->getRepository(User::class)->findAll()[2];
        $this->clientAuth->request('GET', "/users/{$user->getId()}/edit");
        $crawler = $this->clientAuth->submitForm("Modifier", [
            "user[username]" => "User".uniqid(),
            "user[password][first]" => "admin",
            "user[password][second]" => "admin",
            "user[email]" => "test".uniqid()."@test.test",
            "user[role]" => Role::USER,
        ]);

        //Test Redidect to task list
        $this->assertEquals(302, $this->clientAuth->getResponse()->getStatusCode());
        $this->assertContains('users', $crawler->filter('a')->text());
        //Test Alert message
        $crawler = $this->clientAuth->request('GET', "/users");
        $this->assertNotEmpty($crawler->filter('div.alert-success')->text());
    }
}
