<?php

namespace Anteris\Selenium;

use Anteris\Selenium\Commands\ServeCommand;
use Symfony\Component\Console\Application;

/**
 * This application simply runs a Selenium server that can be used by a Selenium
 * client. It is loosly based on the work of Spatie.
 * 
 * @see https://github.com/spatie/selenium-client Spatie's Selenium Client
 * 
 * @author Aidan Casey <aidan.casey@Anteris.com>
 */
class Console extends Application
{
    /**
     * Starts up the application and registers the serve command.
     */
    public function __construct()
    {
        parent::__construct('Selenium Server', '1.0');

        $this->addCommands([
            new ServeCommand,
        ]);
    }
}
