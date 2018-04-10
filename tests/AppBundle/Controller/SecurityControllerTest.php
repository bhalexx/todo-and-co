<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Tests\AppBundle\SetupTest;

class SecurityControllerTest extends SetupTest
{
    public function testLoginAction()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('form')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'yoda';
        $form['_password'] = 'yoda';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Bienvenue sur Todo List")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Gestion des tâches")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("Gestion des utilisateurs")')->count());

        $roles = $this->client->getContainer()->get('security.token_storage')->getToken()->getUser()->getRoles();
        $this->assertNotContains('ROLE_ADMIN', $roles);
    }

    public function testLoginAsAdminAction()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('form')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'bhalexx';
        $form['_password'] = 'bhalexx';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Bienvenue sur Todo List")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Gestion des tâches")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Gestion des utilisateurs")')->count());

        $roles = $this->client->getContainer()->get('security.token_storage')->getToken()->getUser()->getRoles();
        $this->assertContains('ROLE_ADMIN', $roles);
    }

    public function testBadCredentials()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('form')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'password';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div.alert-danger:contains("Invalid credentials")')->count());
    }

    public function testLogout()
    {
        $this->logIn();

        $this->client->request('GET', '/logout');
        $this->client->followRedirect();

        $response = $this->client->getResponse();
        $this->assertSame(302, $response->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('form')->count());
    }
}
