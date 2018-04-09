<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Tests\AppBundle\SetupTest;

class TaskTest extends SetupTest
{
    public function testTask()
    {
        $user = $this->entityManager->getRepository('AppBundle:User')->findOneByUsername('darkdark');

        $task = new Task();
        $task->setTitle('Etoile Noire');
        $task->setContent('Récupérer les plans volés par les rebelles');
        $task->setAuthor($user);
        $task->setCreatedAt(new \DateTime('2017-12-23 03:10:00'));
        $task->toggle(true);
        $this->entityManager->persist($task);

        $this->entityManager->flush();

        $this->assertSame(10, $task->getId());
        $this->assertSame('Etoile Noire', $task->getTitle());
        $this->assertSame('Récupérer les plans volés par les rebelles', $task->getContent());
        $this->assertSame($user, $task->getAuthor());
        $this->assertTrue($task->isDone());
        $this->assertEquals(new \DateTime('2017-12-23 03:10:00'), $task->getCreatedAt());
        $this->assertFalse($task->isAnonymous());
        $this->assertTrue($task->isAuthor($user));
    }
}