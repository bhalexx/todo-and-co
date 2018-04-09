<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

    protected function tearDown()
    {
        $this->client = null;
        $this->container = null;
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
