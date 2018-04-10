<?php

namespace Tests\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use AppBundle\Command\LoadFixturesCommand;
use Tests\AppBundle\SetupTest;

class LoadFixturesCommandTest extends SetupTest
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new LoadFixturesCommand($this->entityManager));

        $command = $application->find('todolist:fixtures:load');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--environment' => 'test'
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Dropping database', $output);
        $this->assertContains('Create database', $output);
        $this->assertContains('Create database schema', $output);
        $this->assertContains('Data successfully loaded', $output);
    }
}