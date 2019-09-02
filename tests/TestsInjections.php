<?php
namespace App\Tests;

use App\Entity\Role;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

trait TestsInjections
{

    /**
     * @var $client KernelBrowser
     */
    public $clientAuth;

    /**
     * @var $client KernelBrowser
     */
    public $clientNotLog;

    /**
     * @var $client KernelBrowser
     */
    private $client;

    public function getRepository(string $name): ObjectRepository
    {
        $kernel = self::bootKernel();
        return $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager()->getRepository($name);
    }

    public function getClientLogged($role = Role::ADMIN): KernelBrowser
    {
        $user = ($role === Role::ADMIN) ? [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ]:[
            'PHP_AUTH_USER' => 'User 1',
            'PHP_AUTH_PW'   => 'admin',
        ];
        $this->clientAuth = static::createClient([], $user);
        return $this->clientAuth;
    }

    public function getClientNotLog(): KernelBrowser
    {
        if (!$this->clientNotLog) $this->clientNotLog = static::createClient();
        return $this->clientNotLog;
    }

    /**
     * Get if route is disable for not logged user
     */
    public function assertSafeRoute(string $route, string $redirectRoute = '/login')
    {
        $crawler = $this->clientNotLog->request('GET', $route);
        $this->assertEquals(302, $this->clientNotLog->getResponse()->getStatusCode());
        $this->assertSame($redirectRoute, $crawler->filter('a')->text());
    }

    /**
     * Check route for logged user
     */
    public function assertCheckRoute(string $route, int $code = 200, string $redirectRoute = "/login"): Crawler
    {
        $crawler = $this->clientAuth->request('GET', $route);
        $this->assertEquals($code, $this->clientAuth->getResponse()->getStatusCode());
        if ($code == 302) {
            $this->assertSame($redirectRoute, $crawler->filter('a')->text());
        }
        return $crawler;
    }
}