<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\AbstractBrowser;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    // You can add more tests for the login action, such as testing form submission and login errors.

    // public function testLoginWithValidCredentials()
    // {
    //     $client = static::createClient();
    //     $client->request('POST', '/login', ['username' => 'your_username', 'password' => 'your_password']);
    //     $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    //     $this->assertTrue($client->getResponse()->isRedirect('/home')); // Adjust the redirect URL
    // }

    // public function testLoginWithInvalidCredentials()
    // {
    //     $client = static::createClient();
    //     $client->request('POST', '/login', ['username' => 'invalid_username', 'password' => 'invalid_password']);
    //     $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    //     $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials.'); // Adjust the selector and error message
    // }

    public function testLogout()
    {
        $client = static::createClient();

        $client->request('GET', '/logout');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

}
