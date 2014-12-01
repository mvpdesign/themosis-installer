<?php

namespace MVPDesign\ThemosisInstaller;

class Config
{
    /**
     * database name
     *
     * @var string
     */
    private $dbName;

    /**
     * get the database name
     *
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * set the database name
     *
     * @param  string $dbName
     * @return void
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }
}
