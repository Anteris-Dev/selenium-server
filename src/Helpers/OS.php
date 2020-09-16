<?php

namespace Anteris\Selenium\Server\Helpers;

class OS
{
    /**
     * Returns our best guess at the operating system.
     */
    public static function name(): string
    {
        if (strpos(PHP_OS_FAMILY, 'Windows') !== false) {
            return 'Windows';
        }

        if (strpos(PHP_OS_FAMILY, 'Darwin') !== false) {
            return 'Mac OS';
        }

        return 'Linux';
    }

    /**
     * Returns the OS name in one-word form.
     */
    public static function oneWordName(): string
    {
        switch (self::name()) {
            case 'Windows':
                return 'windows';
            break;

            case 'Mac OS':
                return 'macos';
            break;

            default:
                return 'linux';
            break;
        }
    }
}
