<?php

namespace Tests\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Tests\AppBundle\SetupTest;

class UserTest extends SetupTest
{
    private $validator;

    public function setUp()
    {
        parent::setUp();
        $this->validator = self::$kernel->getContainer()->get('validator');
    }

    public function testConstructor()
    {
        $user = new User();

        $this->assertInstanceOf(ArrayCollection::class, $user->getTasks());
    }

    public function testId()
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

        $this->assertSame(6, $user->getId());
    }

    public function testUsername()
    {
        $user = new User();

        $this->assertSame(null, $user->getUsername());

        $user->setUsername('Leia');
        $this->assertSame('Leia', $user->getUsername());
    }

    public function testUsernameValidation()
    {
        $user = new User();
        $user->setUsername(null);
        $user->setEmail('leiaa@test.com');

        $errors = $this->validator->validate($user);
        $this->assertEquals(1, count($errors));
    }

    public function testUsernameUnicityValidation()
    {
        $user = new User();
        $user->setUsername('Leia');
        $user->setEmail('leia@test.com');

        $errors = $this->validator->validate($user);
        $this->assertEquals(1, count($errors));
    }

    public function testPassword()
    {
        $user = new User();

        $this->assertSame(null, $user->getPassword());

        $user->setPassword('password');
        $this->assertSame('password', $user->getPassword());
    }

    public function testEmail()
    {
        $user = new User();

        $this->assertSame(null, $user->getEmail());

        $user->setEmail('leia@test.com');
        $this->assertSame('leia@test.com', $user->getEmail());
    }

    public function testEmailValidation()
    {
        $user = new User();
        $user->setUsername('Leia');
        $user->setEmail(null);

        $errors = $this->validator->validate($user);
        $this->assertEquals(1, count($errors));
    }

    public function testEmailUnicityValidation()
    {
        $user = new User();
        $user->setUsername('Leia');
        $user->setEmail('leia@test.com');

        $errors = $this->validator->validate($user);
        $this->assertEquals(1, count($errors));
    }

    public function testRoles()
    {
        $user = new User();

        $this->assertSame(['ROLE_USER'], $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertSame(['ROLE_ADMIN'], $user->getRoles());
    }

    public function testEraseCredentials()
    {
        $user = new User();
        $this->assertNull($user->eraseCredentials());
    }

    public function testSalt()
    {
        $user = new User();
        $this->assertNull($user->getSalt());
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