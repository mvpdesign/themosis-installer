<?php

namespace spec\MVPDesign\ThemosisInstaller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/*
 * This class requires an .env.{$environment}.php file and codeception installed to the project root to function. As such these tests also require those files in order to function properly.
 */
class CodeceptionConfigSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MVPDesign\ThemosisInstaller\CodeceptionConfig');
    }

    function it_should_set_the_env_files_path_when_found(){

        $this->setEnvironmentFilePath('the/real/url/is/found/dynamically/env.local.php')->shouldBeString();
    
    }

    function it_should_get_the_env_file_when_requested()
    {   

        $this->setEnvironmentFilePath('the/real/url/is/found/dynamically/env.local.php')->shouldBeString();

        $this->getEnvironmentFilePath()->shouldBeString();
    }

    function it_should_set_the_codeception_acceptance_yml_file_when_found()
    {
        $this->setAcceptanceConfigYmlPath('the/real/url/is/found/dynamically/acceptance.yml')->shouldBeString();
    }

    function it_should_return_true_if_the_update_was_successful()
    {
        $this->updateWith('local')->shouldReturn(true);
    }

    function it_should_throw_an_exception_if_a_bad_environment_is_passed()
    {
        $this->shouldThrow("Exception")->during('updateWith',['fdssdfsd']);
    }
}

