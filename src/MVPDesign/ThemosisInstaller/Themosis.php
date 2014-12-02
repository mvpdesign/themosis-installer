<?php

namespace MVPDesign\ThemosisInstaller;

use Composer\IO\IOInterface;
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

        $this->io->write('Installation complete.');
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
}
