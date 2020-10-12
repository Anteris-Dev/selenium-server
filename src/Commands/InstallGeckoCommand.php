<?php

namespace Anteris\Selenium\Server\Commands;

use Anteris\Helper\OS;
use Anteris\Selenium\Server\Installers\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallGeckoCommand extends Command
{
    public function __construct()
    {
        parent::__construct('install:gecko');

        // Set some information about this command
        $this->setDescription('Installs the GeckoDriver for the Selenium server.');

        // Set some options for this command
        $this->addOption('driver-version', 'd', InputOption::VALUE_OPTIONAL, 'Sets which version of GeckoDriver should be installed.', 'latest');
        $this->addOption('os', 'o', InputOption::VALUE_OPTIONAL, 'Sets the operating system we are installing for.', OS::shortName());
    }

    /**
     * Installs the GeckoDriver to the /bin directory in the root of this project.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installer = new Installer($input, $output);
        $installer->setDriver('gecko');
        $installer->setOs($input->getOption('os'));
        $installer->setVersion($input->getOption('driver-version'));

        return $installer->install();
    }
}
