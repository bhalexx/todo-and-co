<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;

class UpdateOldTasksCommand extends Command
{
    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();

        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setName('todolist:oldtasks:update')
            ->setDescription('Updates old tasks without author to set anonymous author')
            ->setHelp('This command updates old tasks without authors')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        // Get anonymous user
        $anonymous = $this->getAnonymousUser();

        $tasks = $this->em->getRepository('AppBundle:Task')->findAll();
        $count = 0;
        foreach ($tasks as $task) {
            if (null === $task->getAuthor()) {
                $task->setAuthor($anonymous);
                $count++;
            }
        }
        $this->em->flush();

        $feedback = $count === 0 ? 'No task without author were found.' : 'All users without author have been updated with anonymous author';

        $output->writeln($feedback);
    }

    /**
     * Get anonymous user
     *
     * @return User
     */
    private function getAnonymousUser()
    {
        $existing = $this->em->getRepository('AppBundle:User')->findOneByUsername('Anonymous');
        $user = $existing !== null ?: $this->createAnonymousUser();

        return $user;
    }

    /**
     * Create anonymous user
     *
     * @return User
     */
    private function createAnonymousUser()
    {
        $anonymous = new User();
        $plainPassword = 'anonymousPassword';
        $encoded = $this->encoder->encodePassword($anonymous, $plainPassword);

        $anonymous->setUsername('Anonymous');
        $anonymous->setPassword($encoded);
        $anonymous->setEmail('anonymous@anonym.ous');
        $anonymous->setRoles(['ROLE_USER']);

        $this->em->persist($anonymous);
        $this->em->flush();

        return $anonymous;
    }
}