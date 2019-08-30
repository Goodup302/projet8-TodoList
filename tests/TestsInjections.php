<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

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

    public function getClient($args = []): KernelBrowser
    {
        return static::createClient([], $args);
    }

    public function getClientLogged(): KernelBrowser
    {
        if (!$this->clientAuth) $this->clientAuth = $this->getClient([
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ]);
        return $this->clientAuth;
    }

    public function getClientNotLog(): KernelBrowser
    {
        if (!$this->clientNotLog) $this->clientNotLog = $this->getClient();
        return $this->clientNotLog;
    }

    /**
     * @param $route
     * Get if root is disable for not logged user
     */
    public function assertSafeRoute($route)
    {
        $this->clientNotLog->request('GET', $route);
        $this->assertEquals(302, $this->clientNotLog->getResponse()->getStatusCode());
    }
}