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
	private $workingDirectory;

	/**
     * An Array of files in the current directory
     *
     * @var $filesInCurrentDirectory
     */
	private $filesInCurrentDirectory;
    /**
     * The site url to update the Codeception config with
     *
     * @var $site_url
     */
	private $siteUrl;

    /**
     * The path to the .env.{$environment}.php file
     *
     * @var $environmentFilePath
     */
	private $environmentFilePath;

	private $baseDirectory;
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
     * @var $possibleEnvironments
     */
	private $possibleEnvironments = array( 
 
				"local" => "local",
			    "staging" => "staging",
				"production" => "production"
			
			);

	/**
	 * Sets the environment property
	 *
	 * @return void
	 */
	private function setEnvironment($environment)
	{

		$this->environment = $environment;

	}

	/**
	 * Sets the working directory
	 *
	 * @return void
	 */
	private function setWorkingDirectory()
	{

		$this->workingDirectory = getcwd();

	}

	/**
	 * Sets the path to the environment file
	 * 
     * @param string $FilePath
     *
	 * @return string $this->environmentFilePath
	 */
	public function setEnvironmentFilePath($FilePath)
	{
		return $this->environmentFilePath = $FilePath;
	}

	/**
	 * Gets the path to the acceptance suite config file 
	 *
	 * @return string $this->acceptanceConfigDirectory
	 */
	public function setAcceptanceConfigYmlPath()
	{

		$TestingDirectory = $this->getPathToTestingDir();

		return  $this->acceptanceConfigFilePath = $TestingDirectory . '/'. 'acceptance.suite.yml';
	
	}

	/**
	 * Sets the filesInCurrentDirectory property
	 *
	 * @param string $current_directory
	 *
	 * @return $this->filesInCurrentDirectory
	 */
	private function setFilesInCurrentDirectory($currentDirectory)
	{

		return $this->filesInCurrentDirectory = scandir($currentDirectory);

	}

	/**
	 * Sets the path to the testing directory
	 * 
	 * @param string pathToTestingDir
	 *
	 * @return string $this->pathToTestingDir
	 */
	private function setPathToTestingDirectory($pathToTestingDir)
	{
		return $this->pathToTestingDir = $pathToTestingDir;
	}

	/**
	 * Sets the siteUrl property from a WP_HOME key in the specified .env.$environment.php file
	 * 
	 * @param array $environmentArray
	 *
	 * @return string $this->siteUrl
	 */
	private function setSiteUrlFrom($environmentArray)
	{

		if ( array_key_exists( 'WP_HOME', $environmentArray) )
		{
			return $this->siteUrl = $environmentArray['WP_HOME'];
		}

	}

	private function setCodeceptionConfigPath($codeceptionConfigPath)
	{

		return $this->codeceptionConfigPath = $codeceptionConfigPath;

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

	private function setAcceptanceConfigFile($FileAsArray)
	{
		return $this->acceptanceConfigFile = $FileAsArray;
	}

	/**
	 * Gets the environment property 
	 *
	 * @return string $this->environment
	 */
	private function getEnvironment()
	{
		return $this->environment;
	}
	/**
	 * Gets the environmentFilePath property 
	 *
	 * @return string $this->environmentFile
	 */
	public function getEnvironmentFilePath()
	{
		return $this->environmentFilePath;
	}

	/**
	 * Gets the workingDirectory property 
	 *
	 * @return string $this->workingDirectory
	 */
	private function getWorkingDirectory()
	{
		return $this->workingDirectory;
	}

	/**
	 * Gets the pathToTestingDir property 
	 *
	 * @return string $this->pathToTestingDir
	 */
	private function getPathToTestingDir()
	{
		return $this->pathToTestingDir;
	}

	/**
	 * Gets the acceptanceConfigFile property 
	 *
	 * @return string $this->acceptanceConfigFile
	 */
	private function getAcceptanceConfigFile()
	{
		return $this->acceptanceConfigFile;
	}

	/**
	 * Gets the acceptanceConfigFilePath property 
	 *
	 * @return string $this->acceptanceConfigFilePath
	 */
	private function getAcceptanceConfigFilePath()
	{
		return $this->acceptanceConfigFilePath;
	}

	/**
	 * Gets the siteUrl property 
	 *
	 * @return string $this->siteUrl
	 */
	private function getSiteUrl()
	{
		return $this->siteUrl;
	}

	/**
	 * Gets the rootDirectory property 
	 *
	 * @return string $this->rootDirectory
	 */
	private function getRootDirectory()
	{
		return $this->rootDirectory;
	}

	/**
	 * Gets the EnvironmentArray property 
	 *
	 * @return string $this->EnvironmentArray
	 */
	private function getEnvironmentArray()
	{
		return $this->EnvironmentArray;
	}

	/**
	 * Gets the getFilesInCurrentDirectory property 
	 *
	 * @return string $this->getFilesInCurrentDirectory
	 */
	private function getFilesInCurrentDirectory()
	{
		return $this->filesInCurrentDirectory;
	}

	/**
	 * Gets the codeceptionConfigPath property 
	 *
	 * @return string $this->codeceptionConfigPath
	 */
	private function getCodeceptionConfigPath()
	{
		return $this->codeceptionConfigPath;
	}

	/**
	 * Gets the possibleEnvironments property 
	 *
	 * @return string $this->possibleEnvironments
	 */
	private function getPossibleEnvironments()
	{
		return $this->possibleEnvironments;
	}

	/**
	 * Returns a string containing the project's testing directory
	 *
	 * @param string $currentLineOfYmlFile
	 *
	 * @return string
	 */
	private function getTestingDirectoryFrom($currentLineOfYmlFile)
	{
		return trim(preg_replace('/\w+:/', ' ', $currentLineOfYmlFile));
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

		$this->setEnvironment($environment);

		$this->setWorkingDirectory();

        $this->findEnvironmentFilePath();

        $this->setEnvironmentArrayFromEnvironmentFile();

		$this->setSiteUrlFrom($this->getEnvironmentArray());

        $this->findCodeceptionConfigFile($this->getWorkingDirectory());

        $CodeceptionYmlFile = $this->convertCodeceptionYmlToArray();

        $this->getTestingDirectoryIn($CodeceptionYmlFile);

        $this->setAcceptanceConfigYmlPath();

        $this->convertAcceptanceConfigToArray();

        return $this->WriteProjectEnvironmentToAcceptanceConfig();

	}

	/**
	 * Throws exception if environment is not valid
     *
	 * @return bool
     *
	 */
	private function checkForValidEnvironmentData()
	{

		if( $this->environmentIsValid() )
		{
			return true;
		}

		Throw New Exception("The environment filename is not valid. Use Local, Staging, or Production");

		exit();

	}

	/**
	 * Checks if the supplied string is a valid environment
     *
	 * @return bool
	 */
	private function environmentIsValid()
	{

		$environment = $this->getEnvironment();

		$possibleEnvironments = $this->getPossibleEnvironments();

		if ( in_array($environment, $possibleEnvironments) ) return true;
	}

	/**
	 * Finds the environment file's location in the project files
     * 
	 * @return void
	 */
	private function findEnvironmentFilePath()
	{
		$environment = $this->getEnvironment();

		$current_directory = $this->getWorkingDirectory();

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

		while( $this->isValidDirectory($current_directory) )
		{

			$files_in_current_directory = $this->setFilesInCurrentDirectory($current_directory);

			if ( $this->isInDirectory($targetFile) )
			{
				$this->setBaseDirectory($current_directory);

				return $this->setEnvironmentFilePath($current_directory . '/' . $targetFile);

			}

			return $this->iterateOverProjectDirectoriesFor($targetFile, dirname($current_directory));
		}
	}

	private function setBaseDirectory($current_directory)
	{
		$this->baseDirectory = $current_directory;
	}
	/**
	 * Checks if the target file is found in the current directory
	 *
	 * @param string $targetFile
	 *
	 * @return bool
	 */
	private function isInDirectory($targetFile)
	{

		$files_in_current_directory  = $this->getFilesInCurrentDirectory();

		if (in_array($targetFile, $files_in_current_directory)) return true;

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

		$baseDirectory = $this->baseDirectory;

		$targetFile = "codeception.yml";

		$current_directory = $baseDirectory . "/public/wp-content/themes/themosis/";

		$this->setFilesInCurrentDirectory($current_directory);

		if ( $this->isInDirectory($targetFile) )
		{

			$this->setProjectRootDirectory($current_directory);

			return $this->setCodeceptionConfigPath($current_directory . '/' . $targetFile);
		}

	}	

	/**
	 * Checks if the directory is not empty
     *
	 * @return array $file
	 */
	private function isValidDirectory($current_directory)
	{
		if ( false !== ($check_directory = scandir($current_directory)) ) return true;
	}

	/**
	 * Converts YML file to a php array for editing 
     *
	 * @return array $file
	 */
	private function convertCodeceptionYmlToArray()
	{
		$file = $this->getCodeceptionConfigPath();

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

		for($line = 0; $line < count($CodeceptionYmlFile); $line++)
		{

			$currentLineOfYmlFile = $CodeceptionYmlFile[$line];

			if( strpos($currentLineOfYmlFile, 'tests:') )
			{

				return $this->setPathToTestingDirectory($this->getRootDirectory() . '/' . $this->getTestingDirectoryFrom($currentLineOfYmlFile));
				
			}
			
		}

	}

	/**
	 * returns an array of the acceptance suite config file for editing in php
	 *
     * @return array $this->acceptanceConfigFile
	 */
	private function convertAcceptanceConfigToArray()
	{

		$TestingDirectory = $this->getPathToTestingDir();

		return $this->setAcceptanceConfigFile(file($TestingDirectory . '/' . 'acceptance.suite.yml'));

	}

    /**
     * Updates the acceptance config file with the project's url
     *
     * @return bool
     */
	private function WriteProjectEnvironmentToAcceptanceConfig()
	{
        $acceptanceConfigFile = $this->getAcceptanceConfigFile();

        $acceptanceConfigPath = $this->getAcceptanceConfigFilePath();

        $updatedAcceptanceConfigFile = $this->saveEnvironmentUrl();

		file_put_contents($acceptanceConfigPath, $updatedAcceptanceConfigFile);

        return true;
	}

	
    /**
     * Iterates over the acceptance config array to write in the project's url
     *
     * @return array
     */
    private function saveEnvironmentUrl()
    {
    	
    	$acceptanceConfigFile = $this->getAcceptanceConfigFile();

        for($line = 0; $line < count($acceptanceConfigFile); $line++)
        {

            $currentLineOfConfigFile = $acceptanceConfigFile[$line];

            if( strpos($currentLineOfConfigFile, 'url:') )
            {

                $acceptanceConfigFile[$line] = $this->updateUrlOn($currentLineOfConfigFile);

            }

        }

        return $acceptanceConfigFile;
    }

    /**
     * updates a url on the current line of the file being iterated over
     *
     * @param string $currentLineOfConfigFile
     *
     * @return string
     */
    private function updateUrlOn($currentLineOfConfigFile)
    {

    	$environmentUrl = $this->getSiteUrl();

    	return preg_replace('/https?:\/\/[\w\d\W]*(?=\')/', $environmentUrl, $currentLineOfConfigFile);
    
    }

}