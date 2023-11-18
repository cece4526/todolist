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
        // Assume you have a user with ID 1 in your database
        $crawler = $client->request('GET', 'users/edit/'.$user->getId());

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();

        // Replace 'your_email', 'your_username', and 'your_roles' with test data
        $form['user_edit[email]'] = 'test@test.test';
        $form['user_edit[username]'] = 'your_username';
        $form['user_edit[roles]'] = ["ROLE_ADMIN"]; // Adjust the role as needed

        $client->submit($form);

        $this->assertResponseRedirects('/users/list');
    }

    public function testDeleteAction(): void
    {
        $client = static::createClient();

        // Assume you have a user with ID 1 in your database
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@example.com');

        $session = self::getContainer()->get('session');
        $session->set('user_id', $user->getId());
        $session->save();

        $client->setSession($session);
        // Login
        $client->request('GET', '/login');
        $form = $client->getCrawler()->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        // Assume you have a user with ID 1 in your database
        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('delete' . $user->getId());

        $client->request(
            'POST',
            '/delete/'.$user->getId(),
            ['_token' => $csrfToken]
        );

        $this->assertResponseRedirects('/user_list', 302);

        // Optionally, you can check if the user with ID 1 is actually deleted from the database
        $deletedUser = $userRepository->find(1);
        $this->assertNull($deletedUser, 'The user with ID 1 should be deleted.');
    }

}
