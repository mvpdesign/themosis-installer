<?php

namespace spec\MVPDesign\ThemosisInstaller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InstallSpec extends ObjectBehavior
{
    /**
     * it is initializable
     *
     * @return void
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('MVPDesign\ThemosisInstaller\Install');
    }
}
