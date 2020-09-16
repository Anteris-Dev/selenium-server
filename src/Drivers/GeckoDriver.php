<?php

namespace Anteris\Selenium\Server\Drivers;

class GeckoDriver extends AbstractDriver
{
    /**
     * Returns the name of this driver.
     */
    public static function driverName(): string
    {
        return 'gecko';
    }

    /**
     * Returns an array of downloadable versions of Gecko.
     */
    public static function downloads(): array
    {
        return [
            'linux'   => [
                '0.27.0'  => 'https://github.com/mozilla/geckodriver/releases/download/v0.27.0/geckodriver-v0.27.0-linux64.tar.gz',
            ],
            'macos'   => [
                '0.27.0'  => 'https://github.com/mozilla/geckodriver/releases/download/v0.27.0/geckodriver-v0.27.0-macos.tar.gz',
            ],
            'windows' => [
                '0.27.0'  => 'https://github.com/mozilla/geckodriver/releases/download/v0.27.0/geckodriver-v0.27.0-win64.zip',
            ],
        ];
    }

    /**
     * Returns the latest (stable) version of Gecko.
     */
    public static function latestVersion(): string
    {
        return '0.27.0';
    }
}
