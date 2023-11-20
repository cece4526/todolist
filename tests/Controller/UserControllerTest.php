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
        $form['user_edit[roles]'][0]->tick(); 

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

        $crawler = $client->request('GET', 'users/edit/' . $user->getId());

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();

        $form['user_edit[roles]'][0]->untick();
        $client->submit($form);

        $this->assertResponseRedirects('/users/list');

        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testDeleteUser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);
 
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@test.test');
        $userId = $user->getId();

        $crawler = $client->request('POST', '/users/delete/'.$userId);

        $this->assertFalse($client->getResponse()->isRedirect('/user/list'));

        $this->assertEquals(303, $client->getResponse()->getStatusCode());
    }
}
