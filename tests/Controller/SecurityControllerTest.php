<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\AbstractBrowser;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        // Vérifier que la réponse est un rendu de la page de connexion
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Me connecter'); // Remplacez par le texte réel attendu sur votre page de connexion
    }

    public function testLoginRedirectAuthenticatedUser()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@test.test');
        $client->loginUser($user);

        $client->request('GET', '/login');

        // Vérifier que la réponse est une redirection vers la page d'accueil
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/')); // Remplacez par le chemin réel attendu
    }

    public function testLogout()
    {
        $client = static::createClient();
        $client->request('GET', '/logout');

        // Vérifier que la réponse est une redirection (ou une autre logique selon votre configuration)
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

    }

}
