<?php

namespace Anteris\Selenium\Server\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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
        $selenium = __DIR__ . '/../../bin/selenium-server-standalone-3.141.59.jar';

        /**
         * This nasty section determines the Operating System you are running
         * (it defaults to Linux) and sets up that information for the actual start command.
         */
        $startPath      = __DIR__ . '/../../bin';
        $subPath        = '/linux';
        $os             = 'Linux';
        $geckoFile      = 'geckodriver';
        $chromeFile     = 'chromedriver';

        if (strpos(PHP_OS_FAMILY, 'Windows') !== false) {
            $os             = 'Windows';
            $subPath        = '/windows';
            $geckoFile .= '.exe';
            $chromeFile .= '.exe';
        }

        if (strpos(PHP_OS_FAMILY, 'Darwin') !== false) {
            $os      = 'Mac OS';
            $subPath = '/macos';
        }

        /**
         * This section determines which driver you chose and sets up the parameter
         * to be passed to the Selenium Java binary.
         */
        switch (strtolower($input->getOption('driver'))) {
            case 'gecko':
                $driver     = "webdriver.gecko.driver=$startPath/$subPath/$geckoFile";
                $driverType = 'Firefox';
            break;

            case 'chrome':
                $driver     = "webdriver.chrome.driver=$startPath/$subPath/$chromeFile";
                $driverType = 'Chromium';
            break;

            default:
                throw new InvalidOptionException('Driver must be one of chrome or gecko!');
            break;
        }

        /**
         * Now we finally start the process!
         */
        $output->writeln('');
        $output->writeln("<info>Starting Selenium with $driverType driver on $os.</info>");
        $output->writeln('');

        $process = new Process([
            "java",
            "-D$driver",
            "-jar",
            $selenium,
        ]);

        $process->setIdleTimeout(60 * 60);
        $process->setTimeout(60 * 60);

        $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });
    }
}
