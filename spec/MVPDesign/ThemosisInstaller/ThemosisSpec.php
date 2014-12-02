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
        $io = Mockery::mock('Composer\IO\BaseIO')
            ->shouldReceive('isInteractive', 'write')
            ->andReturn(false)->getMock();

        $this->beConstructedWith($io);
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
     * it should return the config
     *
     * @return void
     */
    public function it_should_return_the_config()
    {
        $this->getConfig()->shouldReturnAnInstanceOf('\MVPDesign\ThemosisInstaller\config');
    }

    /**
     * it should return the theme
     *
     * @return void
     */
    public function it_should_return_the_theme()
    {
        $this->getTheme()->shouldReturn('themosis-theme');
    }

    /**
     * it should return is generating salts
     *
     * @return void
     */
    public function it_should_return_is_generating_salts()
    {
        $this->isGeneratingSalts()->shouldReturn(true);
    }

    /**
     * it should set generating salts
     *
     * @return void
     */
    public function it_should_set_set_generating_salts()
    {
        $isGeneratingSalts = false;

        $this->setGeneratingSalts($isGeneratingSalts);
        $this->isGeneratingSalts()->shouldReturn($isGeneratingSalts);
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
    public function it_should_set_set_installing_wordpress()
    {
        $isInstallingWordPress = false;

        $this->setInstallingWordPress($isInstallingWordPress);
        $this->isInstallingWordPress()->shouldReturn($isInstallingWordPress);
    }

}
