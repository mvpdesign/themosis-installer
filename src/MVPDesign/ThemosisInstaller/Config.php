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
     * database user
     *
     * @var string
     */
    private $dbUser;

    /**
     * database password
     *
     * @var string
     */
    private $dbPassword;

    /**
     * database host
     *
     * @var string
     */
    private $dbHost;

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

    /**
     * get the database user
     *
     * @return string
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * set the database user
     *
     * @param  string $dbUser
     * @return void
     */
    public function setDbUser($dbUser)
    {
        $this->dbUser = $dbUser;
    }

    /**
     * get the database password
     *
     * @return string
     */
    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    /**
     * set the database password
     *
     * @param  string $dbPassword
     * @return void
     */
    public function setDbPassword($dbPassword)
    {
        $this->dbPassword = $dbPassword;
    }

    /**
     * get the database host
     *
     * @return string
     */
    public function getDbHost()
    {
        return $this->dbHost;
    }

    /**
     * set the database host
     *
     * @param  string $dbHost
     * @return void
     */
    public function setDbHost($dbHost)
    {
        $this->dbHost = $dbHost;
    }
}
