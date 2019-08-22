<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    use FixturesInjection;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < $this->iteration; $i++) {
            for ($ii = 0; $ii < $this->taskPerUser; $ii++) {
                $task = new Task();
                $task->setContent("Content");
                $task->setCreatedAt(new \DateTime());
                $task->setTitle("Task $i");
                $task->setUser($this->getReference(UserFixtures::class.$i));
                $this->setReference(self::class.$i, $task);
                $task->toggle(rand(0, 1));
                $manager->persist($task);
            }
        }
        for ($i = 0; $i < $this->iteration; $i++) {
            $task = new Task();
            $task->setContent("Content");
            $task->setCreatedAt(new \DateTime());
            $task->setTitle("Task $i");
            $task->toggle(rand(0, 1));
            $manager->persist($task);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
