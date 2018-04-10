<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\SetupTest;

class DefaultControllerTest extends SetupTest
{
    public function testIndexActionNotLoggedIn()
    {
        $this->client->request('GET', '/');

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Connexion")')->count());
    }

    public function testIndexActionLoggedInAsUser()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('html:contains("Bienvenue sur Todo List")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Gestion des tâches")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("Gestion des utilisateurs")')->count());
    }

    public function testIndexActionLoggedInAsAdmin()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('html:contains("Bienvenue sur Todo List")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Gestion des tâches")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Gestion des utilisateurs")')->count());
    }
}
