<?php

namespace App\Tests\Controller;

use App\Form\RegistrationFormType;
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

    public function testSubmitValidForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);
        
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Inscription')->form();
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[username]'] = 'testuser';
        $form['registration_form[roles]'] = [];
        $form['registration_form[agreeTerms]'] = true;
        $form['registration_form[plainPassword][first]'] = 'password123';
        $form['registration_form[plainPassword][second]'] = 'password123';

        // Mocking the EmailVerifier
        $emailVerifierMock = $this->getMockBuilder(EmailVerifier::class)
        ->disableOriginalConstructor()
        ->getMock();

        // Replace the real EmailVerifier service with the mock in the container
        self::getContainer()->set(EmailVerifier::class, $emailVerifierMock);

        $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect('/'));

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

    }
}
