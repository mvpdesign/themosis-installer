<?php

namespace spec\MVPDesign\ThemosisInstaller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery;

class HooksSpec extends ObjectBehavior
{
    /**
     * it is initializable
     *
     * @return void
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType('MVPDesign\ThemosisInstaller\Hooks');
    }

    /**
     * it should have a hook for themosis
     *
     * @return void
     */
    public function it_should_have_a_hook_for_themosis()
    {
        $io = Mockery::mock('Composer\IO\BaseIO')
            ->shouldReceive('isInteractive', 'write')
            ->andReturn(false)->getMock();
        $event = Mockery::mock('Composer\Script\Event')
            ->shouldReceive('getIO')
            ->andReturn($io)->getMock();

        $this->shouldThrow(new \MVPDesign\ThemosisInstaller\InvalidStringLengthException("The string must have a length greater than 0."))->duringThemosis($event);
    }
}
