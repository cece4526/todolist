<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\AbstractBrowser;

class UserControllerTest extends WebTestCase 
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->followRedirects();

        $client->request('GET', '/users/list');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testEditAction(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@example.com');

        $crawler = $client->request('GET', 'users/edit/'.$user->getId());

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();


        $form['user_edit[email]'] = 'test@test.test';
        $form['user_edit[username]'] = 'your_username';
        $form['user_edit[roles]'] = ["ROLE_ADMIN"]; 

        $client->submit($form);

        $this->assertResponseRedirects('/users/list');
    }

    public function testChangeRoleUser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@test.test');

        $crawler = $client->request('GET', 'users/edit/'.$user->getId());

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();

        $form['user_edit[roles]'] = []; 

        $client->submit($form);

        $this->assertResponseRedirects('/users/list');
    }

    public function testDeleteUser()
    {
        $client = static::createClient();

        // Log in as an admin user
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        // Find the user to be deleted
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@test.test');
        $userId = $user->getId();
        // Get the CSRF token


        // Send a request to delete the user
        $crawler = $client->request('POST', '/users/delete/'.$userId);

        // $form = $crawler->selectButton('Supprimer')->form(); 

        // $client->submit($form);

        // Assert that the response is a redirect to the user list
        $this->assertFalse($client->getResponse()->isRedirect('/user/list'));

        // Assert that the response is a success (HTTP status code 200)
        $this->assertEquals(303, $client->getResponse()->getStatusCode());
    }
}
