<?php

namespace Anteris\Selenium\Server\Commands;

use Anteris\Selenium\Server\Helpers\OS;
use Anteris\Selenium\Server\Installers\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallChromeCommand extends Command
{
    public function __construct()
    {
        parent::__construct('install:chrome');

        // Set some information about this command
        $this->setDescription('Installs the ChromeDriver for the Selenium server.');

        // Set some options for this command
        $this->addOption('driver-version', 'd', InputOption::VALUE_OPTIONAL, 'Sets which version of ChromeDriver should be installed.', 'latest');
        $this->addOption('os', 'o', InputOption::VALUE_OPTIONAL, 'Sets the operating system we are installing for.', OS::oneWordName());
    }

    /**
     * Installs the ChromeDriver to the /bin directory in the root of this project.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installer = new Installer($input, $output);
        $installer->setDriver('chrome');
        $installer->setOs($input->getOption('os'));
        $installer->setVersion($input->getOption('driver-version'));

        return $installer->install();
    }
}
