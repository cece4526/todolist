<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        $client->request('GET', '/tasks/');
        $this->assertResponseIsSuccessful();
    }

    public function testtaskFinished()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        $client->request('GET', '/tasks/finished');
        $this->assertResponseIsSuccessful();
    }

    public function testNewTask()
    {
        $client = static::createClient();


        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        $crawler = $client->request('GET', '/tasks/new');
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test Task',
            'task[content]' => 'This is a test task content.',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/tasks/');

    }

    public function testEditTask()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle('Test Task');
        $crawler = $client->request('GET', '/tasks/edition/'.$task->getId());
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Updated Test Task',
            'task[content]' => 'This is the updated content.',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/tasks/');

    }

    public function testToggleTask()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle('Updated Test Task');

        $client->request('GET', '/tasks/toggle/'.$task->getId());
        $this->assertResponseRedirects('/tasks/');

    }

    public function testDeleteTask()
    {
        $client = static::createClient();


        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Me connecter')->form();
        $form['email'] = 'admin@example.com';
        $form['password'] = 'password';
        $client->submit($form);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle('Updated Test Task');

        $client->request('GET', '/tasks/delete/'.$task->getId());
        $this->assertResponseRedirects('/tasks/');
    }
}
