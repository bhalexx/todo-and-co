<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use AppBundle\Entity\User;

abstract class SetupTest extends WebTestCase
{
    protected $client;
    protected $container;
    protected $entityManager;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }

    protected function logIn($userRole = 'user')
    {
        $username = $userRole === 'admin' ? 'bhalexx' : 'yoda';

        $session = $this->client->getContainer()->get('session');

        $firewallContext = 'main';

        $user = $this->entityManager->getRepository('AppBundle:User')->findOneByUsername($username);

        $token = new UsernamePasswordToken($user, null, $firewallContext, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function tearDown()
    {
        $this->client = null;
        $this->container = null;
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
