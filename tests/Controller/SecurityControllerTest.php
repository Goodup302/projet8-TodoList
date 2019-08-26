<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    public function testloginAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login', ['_username' => 'admin', '_password' => 'admin']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function logout()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/logout');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
