<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\AbstractBrowser;


class TaskControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testFinishedTaskList()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/finished');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testNewTask()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/new');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testEditTask()
    {
        $client = static::createClient();

        // Assuming there is a task with ID 1 in the database
        $client->request('GET', '/tasks/edition/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testToggleTask()
    {
        $client = static::createClient();

        // Assuming there is a task with ID 1 in the database
        $client->request('GET', '/tasks/toggle/1');

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTask()
    {
        $client = static::createClient();

        // Assuming there is a task with ID 1 in the database
        $client->request('GET', '/tasks/delete/1');

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

}
