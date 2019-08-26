<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
//    public function testindexAction()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }

    public function testlistAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

//        $client->setServerParameters([
//            'PHP_AUTH_USER' => 'admin',
//            'PHP_AUTH_PW'   => 'admin',
//        ]);

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ]);
        $crawler = $client->request('GET', '/tasks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
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
