<?php

namespace Anteris\Selenium\Server;

use Anteris\Helper\OS;
use Anteris\Selenium\Server\Exceptions\InvalidDriverException;
use Anteris\Selenium\Server\Exceptions\InvalidOperatingSystemException;
use Symfony\Component\Process\Process;

/**
 * Creates a Symfony Process to run the Selenium server.
 */
class Server
{
    CONST DRIVER_GECKO  = 'gecko';
    const DRIVER_CHROME = 'chrome';
    const OS_LINUX      = 'linux';
    const OS_MACOS      = 'macos';
    const OS_WINDOWS    = 'windows';

    /** @var string The directory that contains our binaries. */
    protected string $binDirectory = (__DIR__ . '/../bin');

    /** @var string The driver we are to serve up. */
    protected ?string $driver = null;
    
    /** @var string The operating system we are to run on. */
    protected ?string $os = null;

    /**
     * Creates a new Symfony process which can be used to control the server.
     */
    public function createProcess(): Process
    {
        $driverParam = $this->createDriverParameter();
        $jarParam    = $this->getBinDirectory() . '/selenium-server-standalone-3.141.59.jar';

        return new Process([
            "java",
            "-D$driverParam",
            "-jar",
            $jarParam,
        ]);
    }

    /**
     * Creates the driver parameter passed to Selenium.
     */
    protected function createDriverParameter(): string
    {
        // First check the driver
        $driver = $this->getDriver();

        if ($driver === null) {
            throw new InvalidDriverException('A driver must be set!');
        }

        $isChromeDriver = ($driver === self::DRIVER_CHROME);
        $isGeckoDriver  = ($driver === self::DRIVER_GECKO);
        
        if ($isChromeDriver === false && $isGeckoDriver === false) {
            throw new InvalidDriverException("Unsupported driver: $driver");
        }

        // Now build the driver string
        $driverPath     = $this->getBinDirectory() . '/' . $this->getOs();
        $driverString   = '';

        if ($isChromeDriver) {
            $driverPath   = ($driverPath . '/chromedriver');
            $driverString = 'webdriver.chrome.driver=';
        }

        if ($isGeckoDriver) {
            $driverPath   = ($driverPath . '/geckodriver');
            $driverString = 'webdriver.gecko.driver=';
        }

        if ($this->getOs() == self::OS_WINDOWS) {
            $driverPath .= '.exe';
        }

        if (! file_exists($driverPath)) {
            throw new InvalidDriverException("$driverPath does not exist! Did you run the install command?");
        }

        return $driverString . $driverPath;
    }

    /**
     * Returns the folder containing our driver binaries.
     */
    public function getBinDirectory(): string
    {
        return realpath($this->binDirectory);
    }

    /**
     * Returns the current driver.
     */
    public function getDriver(): ?string
    {
        return $this->driver ?? self::DRIVER_GECKO;
    }

    /**
     * Returns the current operating system.
     */
    public function getOs(): ?string
    {
        return $this->os ?? OS::shortName();
    }

    /**
     * Sets the directory of our binaries.
     */
    public function setBinDirectory(string $binDirectory): void
    {
        $this->binDirectory = rtrim($binDirectory, '/\\');
    }

    /**
     * Sets the driver we are to run with.
     */
    public function setDriver(string $driver): void
    {
        $driver = strtolower($driver);

        if (
            $driver !== self::DRIVER_CHROME &&
            $driver !== self::DRIVER_GECKO
        ) {
            throw new InvalidDriverException("Unsupported driver: $driver");
        }

        $this->driver = $driver;
    }

    /**
     * Sets the operating system we are to run on.
     */
    public function setOs(string $os): void
    {
        $os = strtolower($os);

        if (
            $os !== self::OS_LINUX &&
            $os !== self::OS_MACOS &&
            $os !== self::OS_WINDOWS
        ) {
            throw new InvalidOperatingSystemException("Unsupported operating system: $os");
        }

        $this->os = $os;
    }
}
