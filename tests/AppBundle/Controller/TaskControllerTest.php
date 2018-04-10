<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use Tests\AppBundle\SetupTest;

class TaskControllerTest extends SetupTest
{
    public function testListActionNotLoggedIn()
    {
        $this->client->request('GET', '/tasks');

        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Connexion")')->count());
    }

    public function testListButton()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('Consulter la liste des tâches')->link();
        $crawler = $this->client->click($link);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Liste de toutes les tâches qu\'il reste à faire")')->count());
    }

    public function testListActionLoggedIn()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/tasks');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Liste de toutes les tâches qu\'il reste à faire")')->count());
    }

    public function testCreateButton()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('Créer une tâche')->link();
        $crawler = $this->client->click($link);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Créer une tâche")')->count());
        $this->assertSame(1, $crawler->filter('form')->count());
    }

    public function testCreateAction()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre nouvelle tâche';
        $form['task[content]'] = 'Contenu de la nouvelle tâche de test';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("Superbe ! La tâche a bien été ajoutée.")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Titre nouvelle tâche")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Contenu de la nouvelle tâche de test")')->count());
    }

    public function testEditActionAsAuthor()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/tasks/6/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Titre modifié';
        $form['task[content]'] = 'Contenu modifié de la tâche de test';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("Superbe ! La tâche a bien été modifiée.")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Titre modifié")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Contenu modifié de la tâche de test")')->count());
    }

    public function testEditActionAsOtherAuthor()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/tasks/1/edit');

        $response = $this->client->getResponse();

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testToggleUndone()
    {
        $this->logIn();

        $this->client->request('GET', '/tasks/1/toggle');

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("a bien été marquée en cours.")')->count());
    }

    public function testToggleDone()
    {
        $this->logIn();

        $this->client->request('GET', '/tasks/1/toggle');

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("a bien été marquée comme terminée.")')->count());
    }

    public function testDeleteActionAsAuthor()
    {
        $this->logIn();

        $this->client->request('GET', '/tasks/6/delete');

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("Superbe ! La tâche a bien été supprimée.")')->count());
    }

    public function testDeleteActionAsOtherAuthor()
    {
        $this->logIn();

        $this->client->request('GET', '/tasks/1/delete');

        $response = $this->client->getResponse();

        $this->assertEquals(403, $response->getStatusCode());
    }
}
