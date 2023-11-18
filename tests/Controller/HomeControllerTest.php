<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testIndexForAuthenticatedUser()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@test.test');
        $urlGenerator = $client->getContainer()->get('router.default');
        $client->loginUser($user);

        $client->request('GET', '/');

        $client->request('GET', $urlGenerator->generate('home'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testIndexForUnauthenticatedUser()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/login')); // Adjust the redirect URL
    }

}
