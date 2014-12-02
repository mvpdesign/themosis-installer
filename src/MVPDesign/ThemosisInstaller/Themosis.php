<?php

namespace MVPDesign\ThemosisInstaller;

use Composer\IO\IOInterface;

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
     * generating salts
     *
     * @var bool
     */
    private $generatingSalts = true;

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
     * is generating salts
     *
     * @return bool
     */
    public function isGeneratingSalts()
    {
        return $this->generatingSalts;
    }

    /**
     * set generating salts
     *
     * @param  bool $generatingSalts
     * @return void
     */
    public function setGeneratingSalts($generatingSalts)
    {
        $this->generatingSalts = $generatingSalts;
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
}
