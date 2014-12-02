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
}
