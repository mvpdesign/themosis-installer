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
     * constructor
     */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }
}
