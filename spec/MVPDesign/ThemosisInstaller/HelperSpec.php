<?php

namespace spec\MVPDesign\ThemosisInstaller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HelperSpec extends ObjectBehavior
{
    /**
     * get matches
     *
     * @return array
     */
    public function getMatchers()
    {
        return array(
            'haveLength' => function ($subject, $length) {
                return strlen($subject) === $length;
            }
        );
    }

    /**
     * it is initializable
     *
     * @return void
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType('MVPDesign\ThemosisInstaller\Helper');
    }

    /**
     * it should generate random strings
     *
     * @return void
     */
    public function it_should_generate_random_strings()
    {
        $this->generateRandomString(8)->shouldHaveLength(8);
        $this->generateRandomString(16)->shouldHaveLength(16);
        $this->generateRandomString(32)->shouldHaveLength(32);
        $this->generateRandomString()->shouldHaveLength(64);
    }
}
