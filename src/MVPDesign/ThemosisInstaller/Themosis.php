<?php

namespace MVPDesign\ThemosisInstaller;

use Composer\IO\IOInterface;
use Symfony\Component\Process\Process;
use MVPDesign\ThemosisInstaller\Config;
use MVPDesign\ThemosisInstaller\Helper;

class Themosis
{
    /**
     * io interface
     *
     * @var IOInterface
     */
    private $io;

    /**
     * config
     *
     * @var Config
     */
    private $config;

    /**
     * theme
     *
     * @var string
     */
    private $theme = 'themosis-theme';

    /**
     * generating wordpress salts
     *
     * @var bool
     */
    private $generatingWordPressSalts = true;

    /**
     * install wordpress
     *
     * @var bool
     */
    private $installingWordPress = true;

    /**
     * constructor
     */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * get the config
     *
     * @return Config
     */
    public function getConfig()
    {
        if (! $this->config) {
            $this->config = new Config;
        }

        return $this->config;
    }

    /**
     * get the theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * is generating wordpress salts
     *
     * @return bool
     */
    public function isGeneratingWordPressSalts()
    {
        return $this->generatingWordPressSalts;
    }

    /**
     * set generating wordpress salts
     *
     * @param  bool $generatingWordPressSalts
     * @return void
     */
    public function setGeneratingWordPressSalts($generatingWordPressSalts)
    {
        $this->generatingWordPressSalts = $generatingWordPressSalts;
    }

    /**
     * is installing wordpress
     *
     * @return bool
     */
    public function isInstallingWordPress()
    {
        return $this->installingWordPress;
    }

    /**
     * set installing wordpress
     *
     * @param  bool $installingWordPress
     * @return void
     */
    public function setInstallingWordPress($installingWordPress)
    {
        $this->installingWordPress = $installingWordPress;
    }

    /**
     * ask configuration questions
     *
     * @return void
     */
    public function askConfigQuestions()
    {
        $config = $this->getConfig();

        if ($this->io->isInteractive()) {
            // get answers to our questions
            $dbName = $this->io->ask(
                Helper::formatQuestion('Database name', $config->getDbName()),
                $config->getDbName()
            );
            $dbUser = $this->io->ask(
                Helper::formatQuestion('Database user', $config->getDbUser()),
                $config->getDbUser()
            );
            $dbPassword = $this->io->ask(
                Helper::formatQuestion('Database passsword', $config->getDbPassword()),
                $config->getDbPassword()
            );
            $dbHost = $this->io->ask(
                Helper::formatQuestion('Database host', $config->getDbHost()),
                $config->getDbHost()
            );
            $environment = $this->io->askAndValidate(
                Helper::formatQuestion('Environment', $config->getEnvironment()),
                "MVPDesign\ThemosisInstaller\Config::validateEnvironment",
                false,
                $config->getEnvironment()
            );
            $siteUrl = $this->io->ask(
                Helper::formatQuestion('Site URL', $config->getSiteUrl()),
                $config->getSiteUrl()
            );
            $generatingWordPressSalts = $this->io->askConfirmation(
                Helper::formatQuestion('Generate WordPress Salts', $this->isGeneratingWordPressSalts() ? 'y' : 'n'),
                $this->isGeneratingWordPressSalts()
            );
            $installingWordpress = $this->io->askConfirmation(
                Helper::formatQuestion('Install WordPress', $this->isInstallingWordPress() ? 'y' : 'n'),
                $this->isInstallingWordPress()
            );

            // save the answers
            $config->setDbName($dbName);
            $config->setDbUser($dbUser);
            $config->setDbPassword($dbPassword);
            $config->setDbHost($dbHost);
            $config->setSiteUrl($siteUrl);
            $config->setEnvironment($environment);

            $this->setGeneratingWordPressSalts($generatingWordPressSalts == 'y' ? true : false);
            $this->setInstallingWordpress($installingWordpress == 'y' ? true : false);

            // extra questions if installing wordpress
            if ($installingWordpress == 'y') {
                $siteTitle = $this->io->ask(
                    Helper::formatQuestion('Site Title', $config->getSiteTitle()),
                    $config->getSiteTitle()
                );

                $adminUser = $this->io->ask(
                    Helper::formatQuestion('Admin User', $config->getAdminUser()),
                    $config->getAdminUser()
                );

                $adminPassword = $this->io->ask(
                    Helper::formatQuestion('Admin Password', $config->getAdminPassword()),
                    $config->getAdminPassword()
                );

                $adminEmail = $this->io->ask(
                    Helper::formatQuestion('Admin Email', $config->getAdminEmail()),
                    $config->getAdminEmail()
                );

                // save the answers
                $config->setSiteTitle($siteTitle);
                $config->setAdminUser($adminUser);
                $config->setAdminPassword($adminPassword);
                $config->setAdminEmail($adminEmail);
            }
        }
    }

    /**
     * runs the installation process
     *
     * @return void
     */
    public function install()
    {
        // generate wordpress salts
        if ($this->isGeneratingWordPressSalts()) {
            $this->generateWordPressSalts();
        }

        // create the env.environment.php file
        $this->createEnvironmentFile();

        if ($this->isInstallingWordpress()) {
            // install the wordpress database
            $this->installWordpress();

            // activate the wordpress theme
            $this->activateWordPressTheme();
        }

        $this->io->write('Themosis installation complete.');
    }

    /**
     * generate wordpress salts
     *
     * @return void
     */
    private function generateWordPressSalts()
    {
        $config = $this->getConfig();

        foreach ($config->getSalts() as $saltKey => $saltValue) {
            $config->setSalt($saltKey, Helper::generateRandomString());
        }

        $this->io->write('Generated WordPress salts.');
    }

    /**
     * create the env.environment.php file
     *
     * @return void
     */
    private function createEnvironmentFile()
    {
        $config = $this->getConfig();

        // load the environment file template
        $envTemplateFileName = ".env.template.php";
        $envTemplate         = file_get_contents($envTemplateFileName);

        // inject the environment variables
        $envTemplate = str_replace('$ENVIRONMENT', ucfirst(strtolower($config->getEnvironment())), $envTemplate);
        $envTemplate = str_replace('$DB_NAME', $config->getDbName(), $envTemplate);
        $envTemplate = str_replace('$DB_USER', $config->getDbUser(), $envTemplate);
        $envTemplate = str_replace('$DB_PASSWORD', $config->getDbPassword(), $envTemplate);
        $envTemplate = str_replace('$DB_HOST', $config->getDbHost(), $envTemplate);
        $envTemplate = str_replace('$WP_SITEURL', $config->getSiteUrl(), $envTemplate);
        foreach ($config->getSalts() as $saltKey => $saltValue) {
            $envTemplate = str_replace('$' . $saltKey, $saltValue, $envTemplate);
        }

        // create the environment file
        $envFileName = '.env.' . strtolower($config->getEnvironment()) . '.php';
        file_put_contents($envFileName, $envTemplate, LOCK_EX);

        $this->io->write('Created the environment file.');
    }

    /**
     * install the wordpress database
     *
     * @return void
     */
    private function installWordPress()
    {
        $config = $this->getConfig();

        $siteUrl       = Helper::validateURL($config->getSiteUrl());
        $siteTitle     = Helper::validateString($config->getSiteTitle());
        $adminUser     = Helper::validateString($config->getAdminUser());
        $adminPassword = Helper::validateString($config->getAdminPassword());
        $adminEmail    = Helper::validateEmail($config->getAdminEmail());

        $command  = 'bin/wp core install';
        $command .= ' --url=' . $siteUrl;
        $command .= ' --title=' . $siteTitle;
        $command .= ' --admin_user=' . $adminUser;
        $command .= ' --admin_password=' . $adminPassword;
        $command .= ' --admin_email=' . $adminEmail;

        $process = new Process($command);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        echo $process->getOutput();
    }

    /**
     * activate the wordpress theme
     *
     * @return void
     */
    private function activateWordPressTheme()
    {
        $command  = 'bin/wp theme activate ' . $this->getTheme();

        $process = new Process($command);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        echo $process->getOutput();
    }
}
