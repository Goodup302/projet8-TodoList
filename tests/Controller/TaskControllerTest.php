<?php

namespace App\Tests\Controller;

use App\Tests\TestsInjections;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use TestsInjections;

    public function setUp()
    {
        $this->getClientLogged();
        $this->getClientNotLog();
    }

    public function testlistAction()
    {
        $this->assertSafeRoute('/tasks');
        $crawler = $this->clientAuth->request('GET', '/tasks');
        $this->assertEquals(200, $this->clientAuth->getResponse()->getStatusCode());
    }

//    public function testcreateAction()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/task/create');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
//
//    public function testeditAction()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/task/0/edit');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
//
//    public function testtoggleTaskAction()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/task/0/toggle');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
//
//    public function testdeleteTaskAction()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/task/0/delete');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
//
//    public function taskProtection()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/login');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
}
