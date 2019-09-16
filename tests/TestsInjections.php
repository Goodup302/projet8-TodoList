<?php
namespace App\Tests;

use App\Entity\Role;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\KernelInterface;

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

    /**
     * @var $em EntityManager
     */
    private $em;

    /**
     * @var $kernel KernelInterface
     */
    private $_kernel;

    /**
     * @var $sessionUser array
     */
    private $sessionUser;

    public function getContainer(): ContainerInterface
    {
        return $this->_kernel->getContainer();
    }

    public function setUpKernel()
    {
        if (!$this->_kernel) {
            $this->_kernel = self::bootKernel();
            $this->em = $this->_kernel->getContainer()
                ->get('doctrine')
                ->getManager();
        }
    }
    public function setUpUser()
    {
        $this->sessionUser['admin'] = [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ];
        $this->sessionUser['user'] = [
            'PHP_AUTH_USER' => 'User 1',
            'PHP_AUTH_PW'   => 'admin',
        ];
    }

    public function getRepository(string $name): ObjectRepository
    {
        $this->setUpKernel();
        return $this->em->getRepository($name);
    }

    public function getClientLogged($role = Role::ADMIN): KernelBrowser
    {
        $this->setUpUser();
        $user = ($role === Role::ADMIN) ? $this->sessionUser['admin'] : $this->sessionUser['user'];
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
     * Check route sucsess response for logged user
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