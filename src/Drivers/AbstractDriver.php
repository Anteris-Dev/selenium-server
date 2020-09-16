<?php

namespace Anteris\Selenium\Server\Drivers;

use Anteris\Selenium\Exceptions\InstallException;

abstract class AbstractDriver
{
    /**
     * Returns the name of this driver.
     */
    abstract public static function driverName(): string;

    /**
     * Returns an array of downloadable versions of this driver.
     */
    abstract public static function downloads(): array;

    /**
     * Returns the latest (stable) version of this driver.
     */
    abstract public static function latestVersion(): string;

    /**
     * Retrieves the download URL based on the version and operating system.
     */
    public static function getDownloadForVersion(string $os, string $version): string
    {
        if ($version === 'latest') {
            $version = static::latestVersion();
        }

        if (! isset(static::downloads()[$os][$version])) {
            throw new InstallException("Invalid version $version for $os!");
        }

        return static::downloads()[$os][$version];
    }
}
