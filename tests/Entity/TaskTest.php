<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;


class TaskTest extends TestCase
{
    public function testGetId()
    {
        $task = new Task();
        $this->assertNull($task->getId());
    }

    public function testSetAndGetCreatedAt()
    {
        $task = new Task();
        $createdAt = new \DateTimeImmutable('2023-01-01 12:00:00');

        $task->setCreatedAt($createdAt);

        $this->assertEquals($createdAt, $task->getCreatedAt());
    }

    public function testSetAndGetUpdateAt()
    {
        $task = new Task();
        $updateAt = new \DateTimeImmutable('2023-01-02 14:30:00');

        $task->setUpdateAt($updateAt);

        $this->assertEquals($updateAt, $task->getUpdateAt());
    }

    public function testSetAndGetTitle()
    {
        $task = new Task();
        $title = 'Sample Task';

        $task->setTitle($title);

        $this->assertEquals($title, $task->getTitle());
    }

    public function testSetAndGetContent()
    {
        $task = new Task();
        $content = 'This is a sample task content.';

        $task->setContent($content);

        $this->assertEquals($content, $task->getContent());
    }

    public function testSetAndGetIsDone()
    {
        $task = new Task();
        $isDone = true;

        $task->setIsDone($isDone);

        $this->assertEquals($isDone, $task->isIsDone());
    }

    public function testSetAndGetUser()
    {
        $task = new Task();
        $user = new User(); // You may need to modify this based on your User entity.

        $task->setUser($user);

        $this->assertEquals($user, $task->getUser());
    }

    // Add more test methods for other getters and setters.

    public function testSetAndGetUserWithNull()
    {
        $task = new Task();

        $task->setUser(null);

        $this->assertNull($task->getUser());
    }

    public function testSetAndGetUserWithAnotherUser()
    {
        $task = new Task();
        $user1 = new User();
        $user2 = new User();

        $task->setUser($user1);
        $task->setUser($user2);

        $this->assertEquals($user2, $task->getUser());
    }

}
