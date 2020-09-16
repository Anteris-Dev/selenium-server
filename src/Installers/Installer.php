<?php

namespace Anteris\Selenium\Server\Installers;

use Anteris\Selenium\Server\Drivers\ChromeDriver;
use Anteris\Selenium\Server\Drivers\GeckoDriver;
use Anteris\Selenium\Server\Exceptions\InstallException;
use GuzzleHttp\Client;
use PharData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class Installer
{
    /** @var string The driver to be installed. */
    protected ?string $driver = null;

    /** @var string The operating system we are installing on. */
    protected ?string $os = null;

    /** @var string The driver version to be installed. */
    protected ?string $version = null;

    /** @var InputInterface Interface for reading input from the console. */
    protected InputInterface $input;

    /** @var OutputInterface Interface for outputing content to the console. */
    protected OutputInterface $output;

    /**
     * This is a console app, so having access to input / output is useful.
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;
    }

    /**
     * Runs the install of the driver.
     */
    public function install(): int
    {
        /**
         * Run some validations...
         */
        if (! $this->getDriver()) {
            throw new InstallException("Please specify the driver you would like to install!");
        }

        if (! $this->getOs()) {
            throw new InstallException("Please specify the operating system you are installing for!");
        }

        if ($this->getDriver() === 'gecko') {
            $driver = new GeckoDriver;
        } else if ($this->getDriver() === 'chrome') {
            $driver = new ChromeDriver;
        }

        /**
         * This section looks scary, but it isn't. Basically we are determining
         * the location and filename to download the driver to.
         */
        $outputDirectory = __DIR__ . '/../../bin/' . $this->getOs() . '/';
        $download = $driver::getDownloadForVersion(
            $this->getOs(),
            $this->getVersion()
        );
        $extension = pathinfo($download, PATHINFO_EXTENSION);
        $filename  = $this->getDriver() . '.' . $extension;
        $outputFile = $outputDirectory . $filename;

        try {
            /**
             * Download the file...
             */
            $this->output->writeln('');
            $this->output->writeln('<info>Downloading ' . $this->getDriver() . ' driver ' . $this->getVersion() . '</info>');
            $this->output->writeln('');

            $this->downloadDriver($download, $outputFile);

            /**
             * Extract the file...
             */
            $this->extractArchive($outputFile, $outputDirectory);

            /**
             * Delete the output file[s].
             */
            unlink($outputFile);

            if ($extension === 'gz') {
                unlink($outputDirectory . $this->getDriver() . '.tar');
            }

            /**
             * Let the user know we succeeded.
             */
            $this->output->writeln('');
            $this->output->writeln(
                'Successfully downloaded ' .
                $this->getDriver() . ' driver ' .
                $this->getVersion() . ' for ' .
                $this->getOs()
            );
            $this->output->writeln('');
        } catch (Throwable $error) {
            $this->output->writeln('');
            $this->output->writeln(
                '<error>Unable to install ' .
                $this->getDriver() . ' driver ' .
                $this->getVersion() . ' for ' .
                $this->getOs() . ': ' . $error->getMessage() .
                '</error>'
            );
            $this->output->writeln('');
            $this->output->writeln(
                'You can manually download the driver below and place it at ' . realpath($outputDirectory)
            );
            $this->output->writeln('');
            $this->output->writeln("<href=$download>$download</>");
            $this->output->writeln('');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Downloads the driver at the specified url to the specified destination.
     */
    protected function downloadDriver(string $url, string $destination): void
    {
        /**
         * First step, create the holding directory if it does not exist.
         */
        $directory = dirname($destination);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        /**
         * Next step, create a progress bar for our file download.
         */
        $progressBar = new ProgressBar($this->output, 100);
        $progressBarCallback = function ($downloadTotal, $downloadedBytes) use ($progressBar) {
            $progressBar->setMaxSteps($downloadTotal);
            $progressBar->advance($downloadedBytes);
        };

        /**
         * Then download our file...
         */
        $client = new Client([]);
        $client->get($url, [
            'sink' => $destination,
            'progress' => $progressBarCallback
        ]);

        /**
         * Complete our progress bar.
         */
        $progressBar->finish();
        $this->output->writeln('');
        $this->output->writeln('');
    }

    /**
     * Extracts the specified archive to the specified destination.
     */
    protected function extractArchive($file, $destination)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if ($extension !== 'zip' && $extension !== 'gz') {
            throw new InstallException("Cannot extract files with extension $extension!");
        }

        $archive = new PharData($file);

        if ($extension === 'gz') {
            $archive->decompress();
        }

        $archive->extractTo($destination, null, true);
    }

    /**
     * Returns which driver we are installing.
     */
    public function getDriver(): ?string
    {
        return $this->driver;
    }

    /**
     * Returns which operating system we are installing for.
     */
    public function getOs(): ?string
    {
        return $this->os;
    }

    /**
     * Returns which version of the driver will be installed.
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Sets the driver we are installing.
     */
    public function setDriver(string $driver)
    {
        $driver = strtolower($driver);

        if (! in_array($driver, ['gecko', 'chrome'])) {
            throw new InstallException("Unrecognized driver $driver!");
        }

        $this->driver = $driver;
    }

    /**
     * Sets the operating system we are installing for.
     */
    public function setOs(string $os)
    {
        $os = strtolower($os);

        if (! in_array($os, ['linux', 'macos', 'windows'])) {
            throw new InstallException("Unrecognized operating system $os!");
        }

        $this->os = $os;
    }

    /**
     * Sets which version of the driver to install.
     */
    public function setVersion(string $version): void
    {
        if (! $this->getDriver()) {
            throw new InstallException('Please set a driver before attempting to set a version!');
        }

        $this->version = $version;
    }
}
