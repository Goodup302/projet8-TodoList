<?php

namespace App\Tests\Controller;

use App\Tests\TestsInjections;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use TestsInjections;

    public function setUp()
    {
        $this->getClientLogged();
        $this->getClientNotLog();
    }
    public function testListUser()
    {
        $this->assertSafeRoute('/users');
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

//    public function testCreateUser()
//    {
//    }
//
//    public function testEditUser()
//    {
//    }
}
