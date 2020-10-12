<?php

namespace Anteris\Selenium\Server\Drivers;

class ChromeDriver extends AbstractDriver
{
    /**
     * Returns the name of this driver.
     */
    public static function driverName(): string
    {
        return 'chrome';
    }

    /**
     * Returns an array of downloadable versions of Gecko.
     */
    public static function downloads(): array
    {
        return [
            'linux'   => [
                '85.0.4183.87'  => 'https://chromedriver.storage.googleapis.com/85.0.4183.87/chromedriver_linux64.zip',
                '86.0.4240.22'  => 'https://chromedriver.storage.googleapis.com/86.0.4240.22/chromedriver_linux64.zip',
            ],
            'macos'   => [
                '85.0.4183.87'  => 'https://chromedriver.storage.googleapis.com/85.0.4183.87/chromedriver_mac64.zip',
                '86.0.4240.22'  => 'https://chromedriver.storage.googleapis.com/86.0.4240.22/chromedriver_mac64.zip',
            ],
            'windows' => [
                '85.0.4183.87'  => 'https://chromedriver.storage.googleapis.com/85.0.4183.87/chromedriver_win32.zip',
                '86.0.4240.22'  => 'https://chromedriver.storage.googleapis.com/86.0.4240.22/chromedriver_win32.zip',
            ],
        ];
    }

    /**
     * Returns the latest (stable) version of Gecko.
     */
    public static function latestVersion(): string
    {
        return '86.0.4240.22';
    }
}
