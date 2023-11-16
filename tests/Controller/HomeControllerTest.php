<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testIndexForAuthenticatedUser()
    {
        $client = static::createClient();

        // Log in a user for the test
        $user = $this->createMock(\App\Entity\User::class);
        $user->method('getId')->willReturn(1);
        
        $client->loginUser($user);

        $client->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testIndexForUnauthenticatedUser()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/login')); // Adjust the redirect URL
    }
}
