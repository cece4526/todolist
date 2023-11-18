<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;


class UserTest extends TestCase
{
    public function testGetId()
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testSetAndGetEmail()
    {
        $user = new User();
        $email = 'test@example.com';

        $user->setEmail($email);

        $this->assertEquals($email, $user->getEmail());
    }

    public function testGetUserIdentifier()
    {
        $user = new User();
        $email = 'test@example.com';

        $user->setEmail($email);

        $this->assertEquals($email, $user->getUserIdentifier());
    }

    public function testSetAndGetRoles()
    {
        $user = new User();
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];

        $user->setRoles($roles);

        $this->assertEquals($roles, $user->getRoles());
    }

    public function testSetAndGetPassword()
    {
        $user = new User();
        $password = 'password123';

        $user->setPassword($password);

        $this->assertEquals($password, $user->getPassword());
    }

    public function testEraseCredentials()
    {
        $user = new User();

        // Just making sure the method runs without errors
        $user->eraseCredentials();

        $this->assertTrue(true);
    }

    public function testSetAndGetUsername()
    {
        $user = new User();
        $username = 'john_doe';

        $user->setUsername($username);

        $this->assertEquals($username, $user->getUsername());
    }

    public function testGetTasks()
    {
        $user = new User();
        $task = new Task();

        $user->addTask($task);

        $this->assertInstanceOf(Task::class, $user->getTasks()->first());
    }

    public function testRemoveTask()
    {

        $user = new User();

        $task = new Task();
        $task->setUser($user);

        $user->addTask($task);

        $this->assertTrue($user->getTasks()->contains($task));

        $user->removeTask($task);

        $this->assertFalse($user->getTasks()->contains($task));

        $this->assertNull($task->getUser());
    }

    public function testIsVerified()
    {
        $user = new User();

        $this->assertFalse($user->isVerified());
    }

    public function testSetIsVerified()
    {
        $user = new User();
        $user->setIsVerified(true);

        $this->assertTrue($user->isVerified());
    }

}
