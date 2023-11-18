<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setTitle("Task $i");
            $task->setContent("Description for Task $i");
            $task->setIsDone(($i % 2 === 0)); // Alternance entre tâches terminées et non terminées


            $manager->persist($task);
        }

        $manager->flush();
    }
}
