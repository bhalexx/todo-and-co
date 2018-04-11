<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;

class LoadFixturesCommand extends Command
{
    private $entityManager;
    private $application;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('todolist:fixtures:load')
            ->setDescription('Loads data fixtures')
            // Option environment - default: dev
            ->addOption('environment', null, InputOption::VALUE_OPTIONAL, 'Application environment', 'dev')
            ->setHelp('This command loads data fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->application = $this->getApplication();
        $this->application->setAutoExit(false);

        if ($input->getOption('environment') === 'test') {
            // Drop database
            $output->writeln('Dropping database');
            $this->dropDatabase();
        }

        // Prepare database environment
        $output->writeln('Create database');
        $this->createDatabase();

        // Create database schema
        $output->writeln('Create database schema');
        $this->createDatabaseSchema();

        //Load fixtures
        $this->loadFixtures();

        $output->writeln('Data successfully loaded');
    }

    /**
     * Executes command to drop database
     */
    private function dropDatabase()
    {
        $options = array('command' => 'doctrine:database:drop', '--force' => true);
        $this->application->run(new ArrayInput($options));
    }

    /**
     * Executes command to create database
     */
    private function createDatabase()
    {
        $options = array('command' => 'doctrine:database:create', '--if-not-exists' => true);
        $this->application->run(new ArrayInput($options));
    }

    /**
     * Executes command to create database schema
     */
    private function createDatabaseSchema()
    {
        $options = array('command' => 'doctrine:schema:create');
        $this->application->run(new ArrayInput($options));
    }

    /**
     * Loads fixtures
     */
    private function loadFixtures()
    {
        $kernel = $this->application->getKernel();

        // Parse fixtures YAML files
        $users = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/users.yml'), true));
        $tasks = Yaml::parse(file_get_contents($kernel->locateResource('@AppBundle/DataFixtures/tasks.yml'), true));

        // Load users
        $this->loadUsers($users);

        //Load tasks
        $this->loadTasks($tasks);

        $this->entityManager->flush();
    }

    /**
     * Loads users
     * @param  array $users
     */
    private function loadUsers($users)
    {
        foreach ($users as $data) {
            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword($data['password']);
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);

            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();
    }

    /**
     * Loads tasks
     * @param  array $tasks
     */
    private function loadTasks($tasks)
    {
        foreach ($tasks as $data) {
            $task = new Task();
            $task->setTitle($data['title']);
            $task->setContent($data['content']);
            $task->setAuthor($this->entityManager->getRepository('AppBundle:User')->findOneById($data['author']));
            $task->toggle($data['isDone']);

            $this->entityManager->persist($task);
        }
    }
}