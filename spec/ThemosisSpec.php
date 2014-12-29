<?php

namespace spec\MVPDesign\ThemosisInstaller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery;

class ThemosisSpec extends ObjectBehavior
{
    /**
     * let
     *
     * @return void
     */
    public function let()
    {
        $io = Mockery::mock('Composer\IO\BaseIO');
        $io->shouldReceive('isInteractive')
           ->andReturn(false);
        $io->shouldReceive('write')
           ->andReturn(false);

        $composer = Mockery::mock('Composer\Composer');

        $event = Mockery::mock('Composer\Script\Event');
        $event->shouldReceive('getIO')
              ->andReturn($io);
        $event->shouldReceive('getComposer')
              ->andReturn($composer);

        $this->beConstructedWith($event);
    }

    /**
     * it is initializable
     *
     * @return void
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType('MVPDesign\ThemosisInstaller\Themosis');
    }

    /**
     * it should return the io
     *
     * @return void
     */
    public function it_should_return_the_io()
    {
        $this->getIO()->shouldReturnAnInstanceOf('\Composer\IO\BaseIO');
    }

    /**
     * it should return composer
     *
     * @return void
     */
    public function it_should_return_composer()
    {
        $this->getComposer()->shouldReturnAnInstanceOf('\Composer\Composer');
    }

    /**
     * it should return the config
     *
     * @return void
     */
    public function it_should_return_the_config()
    {
        $this->getConfig()->shouldReturnAnInstanceOf('\MVPDesign\ThemosisInstaller\Config');
    }

    /**
     * it should return the theme
     *
     * @return void
     */
    public function it_should_return_the_theme()
    {
        $this->getTheme()->shouldReturn('themosis');
    }

    /**
     * it should return the storage path
     *
     * @return void
     */
    public function it_should_return_the_storage_path()
    {
        $this->getStoragePath()->shouldReturn('app/storage');
    }

    /**
     * it should return the config path
     *
     * @return void
     */
    public function it_should_return_the_config_path()
    {
        $this->getConfigPath()->shouldReturn('config');
    }

    /**
     * it should return is generating wordpress salts
     *
     * @return void
     */
    public function it_should_return_is_generating_wordpress_salts()
    {
        $this->isGeneratingWordPressSalts()->shouldReturn(true);
    }

    /**
     * it should set generating wordpress salts
     *
     * @return void
     */
    public function it_should_set_generating_wordpress_salts()
    {
        $isGeneratingWordPressSalts = false;

        $this->setGeneratingWordPressSalts($isGeneratingWordPressSalts);
        $this->isGeneratingWordPressSalts()->shouldReturn($isGeneratingWordPressSalts);
    }

    /**
     * it should return is installing wordpress
     *
     * @return void
     */
    public function it_should_return_is_installing_wordpress()
    {
        $this->isInstallingWordPress()->shouldReturn(true);
    }

    /**
     * it should set installing wordpress
     *
     * @return void
     */
    public function it_should_set_installing_wordpress()
    {
        $isInstallingWordPress = false;

        $this->setInstallingWordPress($isInstallingWordPress);
        $this->isInstallingWordPress()->shouldReturn($isInstallingWordPress);
    }

    /**
     * it should return is configuring themosis theme
     *
     * @return void
     */
    public function it_should_return_is_configuring_themosis_theme()
    {
        $this->isConfiguringThemosisTheme()->shouldReturn(true);
    }

    /**
     * it should set configuring themosis theme
     *
     * @return void
     */
    public function it_should_set_configuring_themosis_theme()
    {
        $isConfiguringThemosisTheme = false;

        $this->setConfiguringThemosisTheme($isConfiguringThemosisTheme);
        $this->isConfiguringThemosisTheme()->shouldReturn($isConfiguringThemosisTheme);
    }

    /**
     * it should install
     *
     * @return void
     */
    public function it_should_install()
    {
        $this->shouldThrow(new \MVPDesign\ThemosisInstaller\InvalidStringLengthException("The string must have a length greater than 0."))->duringInstall();
    }

    /**
     * it should ask config questions
     *
     * @return void
     */
    public function it_should_ask_config_questions()
    {
        $this->askConfigQuestions()->shouldReturn(null);
    }
}
