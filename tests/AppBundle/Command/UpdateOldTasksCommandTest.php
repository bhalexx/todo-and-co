<?php

namespace Tests\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use AppBundle\Command\UpdateOldTasksCommand;
use Tests\AppBundle\SetupTest;

class UpdateOldTasksCommandTest extends SetupTest
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new UpdateOldTasksCommand($this->entityManager));

        $command = $application->find('todolist:oldtasks:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName()
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('No task without author were found', $output);
    }
}