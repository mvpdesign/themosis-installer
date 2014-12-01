<?php

namespace spec\MVPDesign\ThemosisInstaller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    /**
     * it is initializable
     *
     * @return void
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType('MVPDesign\ThemosisInstaller\Config');
    }

    /**
     * it should return a database name
     *
     * @return void
     */
    public function it_should_return_a_database_name()
    {
        $this->getDbName()->shouldReturn(null);
    }

    /**
     * it should set a database name
     *
     * @return void
     */
    public function it_should_set_a_database_name()
    {
        $dbName = Argument::type('string');

        $this->setDbName($dbName);
        $this->getDbName()->shouldReturn($dbName);
    }

    /**
     * it should return a database user
     *
     * @return void
     */
    public function it_should_return_a_database_user()
    {
        $this->getDbUser()->shouldReturn(null);
    }

    /**
     * it should set a database user
     *
     * @return void
     */
    public function it_should_set_a_database_user()
    {
        $dbUser = Argument::type('string');

        $this->setDbUser($dbUser);
        $this->getDbUser()->shouldReturn($dbUser);
    }
}
