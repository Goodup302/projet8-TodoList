<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @var $client KernelBrowser
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testloginSuccess()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connection')->form([
            'username' => 'admin',
            'password' => 'admin',
        ], 'POST');
        $crawler = $this->client->submit($form);
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('/tasks', $crawler->filter('a')->text());
    }

    public function testloginFaild()
    {
        //$this->logIn();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connection')->form([
            'username' => 'wrong_username',
            'password' => 'wrong_password',
        ], 'POST');
        $crawler = $this->client->submit($form);
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('/login', $crawler->filter('a')->text());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'secure_area';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('admin', null, $firewallName, ['ROLE_ADMIN']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
