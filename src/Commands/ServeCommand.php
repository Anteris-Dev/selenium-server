<?php

namespace Anteris\Selenium\Server\Commands;

use Anteris\Helper\OS;
use Anteris\Selenium\Server\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends Command
{
    public function __construct()
    {
        parent::__construct('serve');
        
        // Set some information about this command
        $this->setDescription('Starts a Selenium server at localhost:4444');

        // Gives the user options when it comes to the driver.
        $this->addOption(
            'driver',
            'd',
            InputOption::VALUE_REQUIRED,
            'Determines which driver is used with Selenium (options are chrome OR gecko).',
            'gecko'
        );
    }

    /**
     * Starts a Selenium server at localhost:4444 with the passed driver.
     * If no driver is passed, this defaults to Gecko (Firefox).
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create the server
        $server = new Server;
        $server->setDriver($input->getOption('driver'));

        // Set timeout stuff
        $process = $server->createProcess();
        $process->setIdleTimeout(6000 * 6000);
        $process->setTimeout(6000 * 6000);

        // Startup the server
        $process->start();

        $output->writeln('');
        $output->writeln(
            sprintf(
                '<info>Started Selenium with %s driver on %s</info>',
                $server->getDriver(),
                OS::name($server->getOs())
            )
        );
        $output->writeln('');

        $process->wait();
    }
}
