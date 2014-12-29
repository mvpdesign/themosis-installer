<?php

namespace MVPDesign\ThemosisInstaller;

use Exception;

/*
 * This class searches the project folders for an .env.{$environment}.php file. If found the project's url is grabbed from that file. The class then updates the project's acceptance.yml file with the project's url.
 */
class CodeceptionConfig
{

    /**
     * The type of .env.{$environment}.php file this class will search for
     *
     * @var $environment
     */
	private $environment;

    /**
     * The directory of this classes location
     *
     * @var $working_directory
     */
	private $working_directory;

    /**
     * The site url to update the Codeception config with
     *
     * @var $site_url
     */
	private $site_url;

    /**
     * The path to the .env.{$environment}.php file
     *
     * @var $environmentFilePath
     */
	private $environmentFilePath;

    /**
     * The path to the codeception global config .yml file
     *
     * @var $codeceptionConfigPath
     */
	private $codeceptionConfigPath;

    /**
     * The path to the projects testing directory
     *
     * @var $pathToTestingDir
     */
	private $pathToTestingDir;

    /**
     * The current project's root directory
     *
     * @var $rootDirectory
     */
	private $rootDirectory;

    /**
     * The acceptance suite config .yml file in array form
     *
     * @var $acceptanceConfigFile
     */
    private $acceptanceConfigFile;

    /**
     * The path to the acceptance suite config .yml file
     *
     * @var $acceptanceConfigFilePath
     */
	private $acceptanceConfigFilePath;

    /**
     * The .env.{$environment}.php file in array form
     *
     * @var $EnvironmentArray
     */
    private $EnvironmentArray;

    /**
     * The environments accepted to run this class
     *
     * @var $possible_environments
     */
	private $possible_environments = array( 
 
				"local" => "local",
			    "staging" => "staging",
				"production" => "production"
			
			);

	/**
	 * constructor assigns the current working directory and the $environment
	 */
	public function __construct()
	{
	
	}

	/**
     * @param string $FilePath
     *
	 * @return string $this->environmentFilePath
	 */
	public function setEnvironmentFilePath($FilePath)
	{
		return $this->environmentFilePath = $FilePath;
	}

	/**
	 * Getter for the environmentFilePath property 
	 *
	 * @return string $this->environmentFile
	 */
	public function getEnvironmentFilePath()
	{

		return $this->environmentFilePath;
	
	}

	/**
	 * Gets the path to the acceptance suite config file 
     *
	 * @param string $TestingDirectory
	 *
	 * @return string $this->acceptanceConfigDirectory
	 */
	public function setAcceptanceConfigYmlPath($TestingDirectory)
	{

		return  $this->acceptanceConfigFilePath = $TestingDirectory . '/'. 'acceptance.suite.yml';
	
	}

	/**
	 * Executes the class functions
	 *
     * @param string $environment
     *
	 * @return true or null on failure
	 */
	public function updateWith($environment)
	{

		$this->environment = $environment;

		$this->working_directory = getcwd();

        $this->findEnvironmentFilePath();

        $this->setEnvironmentArrayFromEnvironmentFile();

		$this->setSiteUrl();

        $this->findCodeceptionConfigFile($this->working_directory);

        $CodeceptionYmlFile = $this->convertCodeceptionYmlToArray();

        $this->getTestingDirectoryIn($CodeceptionYmlFile);

        $this->setAcceptanceConfigYmlPath($this->pathToTestingDir);

        $this->convertAcceptanceConfigToArray($this->pathToTestingDir);

        return $this->WriteProjectEnvironmentToAcceptanceConfig();

	}

	/**
	 * Sets the site_url property from a WP_HOME key in the specified .env.$environment.php file
	 *
	 * @return string $this->site_url
	 */
	private function setSiteUrl()
	{
        $environmentArray = $this->EnvironmentArray;

		if ( array_key_exists( 'WP_HOME', $environmentArray) )
		{
			return $this->site_url = $environmentArray['WP_HOME'];
		}

	}


	/**
	 * Checks if the supplied string is a valid environment
     *
	 * @return bool
     *
	 */
	private function checkForValidEnvironmentData()
	{

		$environment = $this->environment;

		$possible_environments = $this->possible_environments;

		if(in_array($environment, $possible_environments))
		{
			return true;
		}

		Throw New Exception("The environment filename is not valid. Use Local, Staging, or Production");

		exit();

	}

	/**
	 * Finds the environment file's location in the project files
     * 
	 * @return void
	 */
	private function findEnvironmentFilePath()
	{
		$environment = $this->environment;

		$current_directory = $this->working_directory;

		$targetFile = ".env.".$environment.".php";

		$this->checkForValidEnvironmentData();

		$this->iterateOverProjectDirectoriesFor($targetFile, $current_directory);

	}

    /**
     * Iterates over the project's folders to find the environment file
     *
     * @param string $targetFile
     * @param string $current_directory
     *
     * @return method $this->setEnvironmentFilePath()
     */
	private function iterateOverProjectDirectoriesFor($targetFile, $current_directory)
	{

		while( false !== ($entry = scandir($current_directory)) )
		{

			$files_in_current_directory = scandir($current_directory);

			if ( in_array($targetFile, $files_in_current_directory) )
			{

				 return $this->setEnvironmentFilePath($current_directory . '/' . $targetFile);

			}

			return $this->iterateOverProjectDirectoriesFor($targetFile, dirname($current_directory));
		}
	}

	/**
	 * If the environment file exists. Require it once. The file is an array
	 * 
	 * @return array $this->EnvironmentArray
	 */
	private function setEnvironmentArrayFromEnvironmentFile()
	{

		if (file_exists($path = $this->getEnvironmentFilePath())) {

            return $this->EnvironmentArray = require_once($path);

        }

	}


	/**
	 * Iterates through project files to find the codeception global config file. Returns the path to that file
     *
	 * @param string $current_directory
	 *
	 * @return string $this->codeceptionConfigPath
	 */
	private function findCodeceptionConfigFile($current_directory)
	{

		$targetFile = "codeception.yml";

		while( false !== ($entry = scandir($current_directory)) )
		{

			$files_in_current_directory = scandir($current_directory);
	
			if ( in_array($targetFile, $files_in_current_directory) )
			{

				$this->setProjectRootDirectory($current_directory);

				return $this->codeceptionConfigPath = $current_directory . '/' . $targetFile;
			}

			return $this->findCodeceptionConfigFile(dirname($current_directory));
		}

	}	

	/**
	 * Sets the root directory of the current project
     *
	 * @param $current_directory
	 *
	 * @return string $this->rootDirectory
	 */
	private function setProjectRootDirectory($current_directory)
	{
	
		$this->rootDirectory = $current_directory;
	
	}

	/**
	 * Converts YML file to a php array for editing 
     *
	 * @return array $file
	 */
	private function convertCodeceptionYmlToArray()
	{
		$file = $this->codeceptionConfigPath;

		return file($file);
	}

	/**
	 * Iterates over the Codeception config file for the testing directory. Returns the path to that directory
     *
	 * @param string $CodeceptionYmlFile
	 *
	 * @return string $this->pathToTestingDir
	 */
	private function getTestingDirectoryIn($CodeceptionYmlFile)
	{

		for($count = 0; $count < count($CodeceptionYmlFile); $count++)
		{

			$current_value = $CodeceptionYmlFile[$count];

			if( strpos($current_value, 'tests:') )
			{

				return $this->pathToTestingDir = $this->rootDirectory . '/' . trim(preg_replace('/\w+:/', ' ', $current_value));
				
			}
			
		}

	}

	/**
	 * returns an array of the acceptance suite config file for editing in php
	 *
     * @param string $TestingDirectory
     *
     * @return array $this->acceptanceConfigFile
	 */
	private function convertAcceptanceConfigToArray($TestingDirectory)
	{

		return $this->acceptanceConfigFile = file($TestingDirectory . '/' . 'acceptance.suite.yml');

	}

    /**
     * Updates the acceptance config file with the project's url
     *
     * @return bool
     */
	private function WriteProjectEnvironmentToAcceptanceConfig()
	{
        $acceptanceConfigFile = $this->acceptanceConfigFile;

        $acceptanceConfigPath = $this->acceptanceConfigFilePath;

        $updatedAcceptanceConfigFile = $this->saveEnvironmentUrlTo($acceptanceConfigFile);

		file_put_contents($acceptanceConfigPath, $updatedAcceptanceConfigFile);

        return true;
	}

    /**
     * Iterates over the acceptance config array to write in the project's url
     *
     * @param array
     *
     * @return array
     */
    private function saveEnvironmentUrlTo($acceptanceConfigFile)
    {
        $environmentUrl = $this->site_url;

        for($count = 0; $count < count($acceptanceConfigFile); $count++)
        {

            $current_value = $acceptanceConfigFile[$count];

            if( strpos($current_value, 'url:') )
            {

                $newUrl = preg_replace('/http:\/\/[\w\d\W]*/', $environmentUrl, $current_value);

                $acceptanceConfigFile[$count] = $newUrl;

            }

        }

        return $acceptanceConfigFile;
    }


}