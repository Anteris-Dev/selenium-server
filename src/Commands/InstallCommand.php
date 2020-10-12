<?php

namespace Anteris\Selenium\Server\Commands;

use Anteris\Helper\OS;
use Anteris\Selenium\Server\Installers\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    public function __construct()
    {
        parent::__construct('install');

        // Set some information about this command
        $this->setDescription('Installs the necessary drivers to run Selenium server.');
    }

    /**
     * Installs both the GeckoDriver and ChromeDriver to the /bin directory in
     * the root of this project.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installer = new Installer($input, $output);
        $installer->setOs(OS::shortName());
        $installer->setDriver('gecko');
        $installer->setVersion('latest');

        if ($installer->install() === 1) {
            return Command::FAILURE;
        }

        $installer->setDriver('chrome');

        if ($installer->install() === 1) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
