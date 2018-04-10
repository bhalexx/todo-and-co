<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Tests\AppBundle\SetupTest;

class TaskTest extends SetupTest
{
    private $validator;
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->validator = self::$kernel->getContainer()->get('validator');

        $this->user = new User();
        $this->user->setUsername('Leia');
        $this->user->setPassword('password');
        $this->user->setEmail('leia@mail.com');
        $this->user->setRoles(['ROLE_USER']);
    }

    public function testConstructor()
    {
        $task = new Task();

        $this->assertFalse($task->isDone());
        $this->assertInstanceOf('DateTime', $task->getCreatedAt());
    }

    public function testId()
    {
        $user = $this->entityManager->getRepository('AppBundle:User')->findOneByUsername('darkdark');

        $task = new Task();
        $task->setTitle('Etoile Noire');
        $task->setContent('Récupérer les plans volés par les rebelles.');
        $task->setAuthor($user);
        $task->setCreatedAt(new \DateTime('2017-12-23 03:10:00'));
        $task->toggle(true);
        $this->entityManager->persist($task);

        $this->entityManager->flush();

        $this->assertSame(10, $task->getId());
    }

    public function testTitle()
    {
        $task = new Task();
        $this->assertNull($task->getTitle());

        $task->setTitle('Etoile Noire');
        $this->assertSame('Etoile Noire', $task->getTitle());
    }

    public function testTitleValidation()
    {
        $task = new Task();
        $task->setTitle(null);
        $task->setContent('Cacher les plans volés aux méchants dans R2.');
        $task->setAuthor($this->user);

        $errors = $this->validator->validate($task);
        $this->assertEquals(1, count($errors));
    }

    public function testContent()
    {
        $task = new Task();
        $this->assertNull($task->getContent());

        $task->setContent('Cacher les plans volés aux méchants dans R2.');
        $this->assertSame('Cacher les plans volés aux méchants dans R2.', $task->getContent());
    }

    public function testContentValidation()
    {
        $task = new Task();
        $task->setTitle('Etoile Noire');
        $task->setContent(null);
        $task->setAuthor($this->user);

        $errors = $this->validator->validate($task);
        $this->assertEquals(1, count($errors));
    }

    public function testCreatedAt()
    {
        $task = new Task();

        $task->setCreatedAt(new \DateTime('2017-12-23 03:10:00'));
        $this->assertEquals(new \DateTime('2017-12-23 03:10:00'), $task->getCreatedAt());
    }

    public function testToggle()
    {
        $task = new Task();
        $this->assertFalse($task->isDone());

        $task->toggle(true);
        $this->assertTrue($task->isDone());
    }

    public function testAuthor()
    {
        $task = new Task();
        $this->assertNull($task->getAuthor());

        $task->setAuthor($this->user);
        $this->assertSame($this->user, $task->getAuthor());
    }

    public function testIsAnonymous()
    {
        $task = new Task();
        $user = new User();
        $user->setUsername('Anonymous');

        $task->setAuthor($user);
        $this->assertTrue($task->isAnonymous());

        $task->setAuthor($this->user);
        $this->assertFalse($task->isAnonymous());
    }

    public function testIsAuthor()
    {
        $task = new Task();

        $task->setAuthor($this->user);
        $this->assertTrue($task->isAuthor($this->user));
    }
}