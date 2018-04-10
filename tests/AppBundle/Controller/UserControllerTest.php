<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Tests\AppBundle\SetupTest;

class UserControllerTest extends SetupTest
{
    public function testListActionNotLoggedIn()
    {
        $this->client->request('GET', '/users');

        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Connexion")')->count());
    }

    public function testListButton()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('Gérer les utilisateurs')->link();
        $crawler = $this->client->click($link);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Liste des utilisateurs ayant accès à la liste de tâches")')->count());
    }

    public function testListActionLoggedInAsUser()
    {
        $this->logIn();

        $this->client->request('GET', '/users');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testListActionLoggedInAsAdmin()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Liste des utilisateurs ayant accès à la liste de tâches")')->count());
    }

    public function testCreateButton()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('Créer un utilisateur')->link();
        $crawler = $this->client->click($link);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Créer un utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('form')->count());
    }

    public function testCreateActionAsUser()
    {
        $this->logIn();

        $this->client->request('GET', '/users/create');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionAsAdmin()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'user';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'pseudo@mail.com';
        $form['user[roles]'] = ['ROLE_USER'];
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("Superbe ! L\'utilisateur a bien été ajouté.")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("pseudo")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("pseudo@mail.com")')->count());
    }

    public function testCreateActionEmptyUsername()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'pseudo@mail.com';
        $form['user[roles]'] = ['ROLE_USER'];
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Vous devez saisir un nom d\'utilisateur.")')->count());
    }

    public function testCreateActionNonUniqueUsername()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'bhalexx';
        $form['user[password][first]'] = 'bhalexx';
        $form['user[password][second]'] = 'bhalexx';
        $form['user[email]'] = 'bhalexx@email.com';
        $form['user[roles]'] = ['ROLE_USER'];
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Ce nom est déjà utilisé par un utilisateur.")')->count());
    }

    public function testCreateActionEmptyEmail()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'bhalexx';
        $form['user[password][first]'] = 'Motdepasse';
        $form['user[password][second]'] = 'Motdepasse';
        $form['user[roles]'] = ['ROLE_USER'];
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Vous devez saisir une adresse email.")')->count());
    }

    public function testCreateActionInvalidEmail()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'bhalexx';
        $form['user[password][first]'] = 'Motdepasse';
        $form['user[password][second]'] = 'Motdepasse';
        $form['user[email]'] = 'email';
        $form['user[roles]'] = ['ROLE_USER'];
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Le format de l\'adresse email n\'est pas correct.")')->count());
    }

    public function testCreateActionNonUniqueEmail()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'user';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'bhalexx@email.com';
        $form['user[roles]'] = ['ROLE_USER'];
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Cet email est déjà utilisé par un utilisateur.")')->count());
    }

    public function testEditActionAsUser()
    {
        $this->logIn();

        $this->client->request('GET', '/users/2/edit');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditActionAsAdmin()
    {
        $this->logIn('admin');

        $crawler = $this->client->request('GET', '/users/2/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'UserToEdit';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'user@mail.com';
        $form['user[roles]'] = ['ROLE_USER'];
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("Superbe ! L\'utilisateur a bien été modifié.")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("UserToEdit")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("user@mail.com")')->count());
    }

    public function testDeleteActionAsUser()
    {
        $this->logIn();

        $this->client->request('GET', '/users/2/delete');

        $response = $this->client->getResponse();

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDeleteActionAsAdmin()
    {
        $this->logIn('admin');

        $this->client->request('GET', '/users/2/delete');

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success:contains("Superbe ! L\'utilisateur a bien été supprimé.")')->count());
    }
}
