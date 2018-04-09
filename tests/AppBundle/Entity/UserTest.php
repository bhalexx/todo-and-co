<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Tests\AppBundle\SetupTest;

class UserTest extends SetupTest
{
    public function testUser()
    {
        $user = new User();
        $user->setUsername('Leia');
        $user->setPassword('Leia');
        $user->setEmail('leia@test.com');
        $user->setRoles(['ROLE_USER']);
        $this->entityManager->persist($user);

        $task = new Task();
        $task->setTitle('Etoile Noire');
        $task->setContent('Donner les plans volés aux méchants à R2D2');
        $task->setAuthor($user);
        $this->entityManager->persist($task);

        $this->entityManager->flush();

        $this->assertSame(5, $user->getId());
        $this->assertSame('Leia', $user->getUsername());
        $this->assertSame('leia@test.com', $user->getEmail());
        $this->assertSame(['ROLE_USER'], $user->getRoles());
        $this->assertNull($user->eraseCredentials());
    }

    public function testUndefinedRoles()
    {
        $user = new User();

        $this->assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testAddTask()
    {
        $user = $this->entityManager->getRepository('AppBundle:User')->findOneByUsername('Leia');

        $task = new Task();
        $task->setTitle('Perso');
        $task->setContent('Embrasser Luke pour rendre Han jaloux.');
        $task->setAuthor($user);

        $user->addTask($task);
        $this->entityManager->persist($user);

        $this->assertCount(2, $user->getTasks());
        $user->removeTask($task);
        $this->assertCount(1, $user->getTasks());
    }
}