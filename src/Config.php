<?php

namespace MVPDesign\ThemosisInstaller;

use MVPDesign\ThemosisInstaller\InvalidEnvironmentException;

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
    private $dbHost = 'localhost';

    /**
     * database prefix
     *
     * @var string
     */
    private $dbPrefix = 'mvpwp_';

    /**
     * environments
     *
     * @var string
     */
    public static $environments = array(
        'local',
        'staging',
        'production'
    );

    /**
     * environment
     *
     * @var string
     */
    private $environment = 'local';

    /**
     * site url
     *
     * @var string
     */
    private $siteUrl = 'http://localhost';

    /**
     * site title
     *
     * @var string
     */
    private $siteTitle;

    /**
     * is site public
     *
     * @var string
     */
    private $isSitePublic = false;

    /**
     * site description
     *
     * @var string
     */
    private $siteDescription;

    /**
     * admin user
     *
     * @var string
     */
    private $adminUser;

    /**
     * admin password
     *
     * @var string
     */
    private $adminPassword;

    /**
     * admin email
     *
     * @var string
     */
    private $adminEmail;

    /**
     * salts
     *
     * @var array
     */
    private $salts = array(
        'AUTH_KEY'         => null,
        'SECURE_AUTH_KEY'  => null,
        'LOGGED_IN_KEY'    => null,
        'NONCE_KEY'        => null,
        'AUTH_SALT'        => null,
        'SECURE_AUTH_SALT' => null,
        'LOGGED_IN_SALT'   => null,
        'NONCE_SALT'       => null
    );

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

    /**
     * get the database prefix
     *
     * @return string
     */
    public function getDbPrefix()
    {
        return $this->dbPrefix;
    }

    /**
     * set the database prefix
     *
     * @param  string $dbPrefix
     * @return void
     */
    public function setDbPrefix($dbPrefix)
    {
        $this->dbPrefix = $dbPrefix;
    }

    /**
     * get the environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * set the environment
     *
     * @param  string $environment
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = Config::validateEnvironment($environment);
    }

    /**
     * validate the environment
     *
     * @param  string $environment
     * @return void
     *
     * @throws InvalidEnvironmentException
     */
    public static function validateEnvironment($environment)
    {
        if (! in_array($environment, Config::$environments)) {
            throw new InvalidEnvironmentException('Valid environments are: ' . implode(', ', Config::$environments) . '.');
        }

        return $environment;
    }

    /**
     * get the site url
     *
     * @return string
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * set the site url
     *
     * @param  string $siteUrl
     * @return void
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    /**
     * get the site title
     *
     * @return string
     */
    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    /**
     * set the site title
     *
     * @param  string $siteTitle
     * @return void
     */
    public function setSiteTitle($siteTitle)
    {
        $this->siteTitle = $siteTitle;
    }

    /**
     * get the namespace slug
     *
     * @return string
     */
    public function getNamespaceSlug()
    {
        return str_replace(' ', '', ucwords($this->siteTitle));
    }

    /**
     * get the site slug
     *
     * @return string
     */
    public function getSiteSlug()
    {
        return str_replace(' ', '-', strtolower($this->siteTitle));
    }

    /**
     * is the site public
     *
     * @return bool
     */
    public function isSitePublic()
    {
        return $this->isSitePublic;
    }

    /**
     * set is site public
     *
     * @param  bool $isSitePublic
     * @return void
     */
    public function setIsSitePublic($isSitePublic)
    {
        $this->isSitePublic = $isSitePublic;
    }

    /**
     * get the site description
     *
     * @return string
     */
    public function getSiteDescription()
    {
        return $this->siteDescription;
    }

    /**
     * set the site description
     *
     * @param  string $siteDescription
     * @return void
     */
    public function setSiteDescription($siteDescription)
    {
        $this->siteDescription = $siteDescription;
    }

    /**
     * get the admin user
     *
     * @return string
     */
    public function getAdminUser()
    {
        return $this->adminUser;
    }

    /**
     * set the admin user
     *
     * @param  string $adminUser
     * @return void
     */
    public function setAdminUser($adminUser)
    {
        $this->adminUser = $adminUser;
    }

    /**
     * get the admin password
     *
     * @return string
     */
    public function getAdminPassword()
    {
        return $this->adminPassword;
    }

    /**
     * set the admin password
     *
     * @param  string $adminPassword
     * @return void
     */
    public function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;
    }

    /**
     * get the admin email
     *
     * @return string
     */
    public function getAdminEmail()
    {
        return $this->adminEmail;
    }

    /**
     * set the admin email
     *
     * @param  string $adminEmail
     * @return void
     */
    public function setAdminEmail($adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }

    /**
     * get salts
     *
     * @return string
     */
    public function getSalts()
    {
        return $this->salts;
    }

    /**
     * get a salt
     *
     * @param  string $key
     * @return bool|string
     */
    public function getSalt($key)
    {
        if (! array_key_exists($key, $this->salts)) {
            return false;
        }

        return $this->salts[$key];
    }

    /**
     * set a salt
     *
     * @param  string $key
     * @param  string $value
     * @return bool|void
     */
    public function setSalt($key, $value)
    {
        if (! array_key_exists($key, $this->salts)) {
            return false;
        }

        $this->salts[$key] = $value;
    }
}
