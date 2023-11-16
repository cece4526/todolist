<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;



class RegistrationControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testVerifyUserEmail()
    {
        $client = static::createClient();


        $user = $this->createMock(\App\Entity\User::class);
        $user->method('getId')->willReturn(1);

        $client->loginUser($user);

        $client->request('GET', '/verify/email');

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testResendEmail()
    {
        $client = static::createClient();

        $user = $this->createMock(\App\Entity\User::class);
        $user->method('getId')->willReturn(1); 

        $client->loginUser($user);

        $client->request('GET', '/resend/email');

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }


}
