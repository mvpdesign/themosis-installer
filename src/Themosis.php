<?php

namespace MVPDesign\ThemosisInstaller;

use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Composer;
use Symfony\Component\Process\Process;
use MVPDesign\ThemosisInstaller\Config;
use MVPDesign\ThemosisInstaller\InvalidEnvironmentException;
use MVPDesign\ThemosisInstaller\Helper;
use MVPDesign\ThemosisInstaller\InvalidStringLengthException;
use MVPDesign\ThemosisInstaller\InvalidEmailException;
use MVPDesign\ThemosisInstaller\InvalidURLException;
use MVPDesign\ThemosisInstaller\InvalidConfirmationException;

class Themosis
{
    /**
     * event
     *
     * @var Event
     */
    private $event;

    /**
     * config
     *
     * @var Config
     */
    private $config;

    /**
     * theme
     *
     * @var string
     */
    private $theme = 'themosis';

    /**
     * storage path
     *
     * @var string
     */
    private $storagePath = 'app/storage';

    /**
     * config path
     *
     * @var string
     */
    private $configPath = 'config';

    /**
     * generating wordpress salts
     *
     * @var bool
     */
    private $generatingWordPressSalts = true;

    /**
     * configuring themosis
     *
     * @var bool
     */
    private $configuringThemosis = true;

    /**
     * installing wordpress
     *
     * @var bool
     */
    private $installingWordPress = true;

    /**
     * configuring themosis theme
     *
     * @var bool
     */
    private $configuringThemosisTheme = true;

    /**
     * installing themosis theme
     *
     * @var bool
     */
    private $installingThemosisTheme = true;

    /**
     * constructor
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * get the io
     *
     * @return IOInterface
     */
    public function getIO()
    {
        return $this->event->getIO();
    }

    /**
     * get composer
     *
     * @return Composer
     */
    public function getComposer()
    {
        return $this->event->getComposer();
    }

    /**
     * get options
     *
     * @return array
     */
    public function getOptions()
    {
        $arguments = $this->event->getArguments();
        $options   = array();

        foreach ($arguments as $argument) {
            list($key, $value) = explode('=', $argument);

            $options[$key] = $value;
        }

        return $options;
    }

    /**
     * get a option
     *
     * @param  string $key
     * @return string
     */
    public function getOption($key)
    {
        $options = $this->getOptions();
        $value   = false;

        if (array_key_exists($key, $options)) {
            $value = $options[$key];
        }

        return $value;
    }

    /**
     * get the config
     *
     * @return Config
     */
    public function getConfig()
    {
        if (! $this->config) {
            $this->config = new Config;
        }

        return $this->config;
    }

    /**
     * get the theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * set the theme
     *
     * @param  string $theme
     * @return string
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }


    /**
     * get the storage path
     *
     * @return string
     */
    public function getStoragePath()
    {
        return $this->storagePath;
    }

    /**
     * get the config path
     *
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configPath;
    }

    /**
     * is generating wordpress salts
     *
     * @return bool
     */
    public function isGeneratingWordPressSalts()
    {
        return $this->generatingWordPressSalts;
    }

    /**
     * set generating wordpress salts
     *
     * @param  bool $generatingWordPressSalts
     * @return void
     */
    public function setGeneratingWordPressSalts($generatingWordPressSalts)
    {
        $this->generatingWordPressSalts = $generatingWordPressSalts;
    }

    /**
     * is configuring themosis
     *
     * @return bool
     */
    public function isConfiguringThemosis()
    {
        return $this->configuringThemosis;
    }

    /**
     * set configuring themosis
     *
     * @param  bool $configuringThemosis
     * @return void
     */
    public function setConfiguringThemosis($configuringThemosis)
    {
        $this->configuringThemosis = $configuringThemosis;
    }

    /**
     * is installing wordpress
     *
     * @return bool
     */
    public function isInstallingWordPress()
    {
        return $this->installingWordPress;
    }

    /**
     * set installing wordpress
     *
     * @param  bool $installingWordPress
     * @return void
     */
    public function setInstallingWordPress($installingWordPress)
    {
        $this->installingWordPress = $installingWordPress;
    }

    /**
     * is configuring themosis theme
     *
     * @return bool
     */
    public function isConfiguringThemosisTheme()
    {
        return $this->configuringThemosisTheme;
    }

    /**
     * set configuring themosis theme
     *
     * @param  bool $configuringThemosisTheme
     * @return void
     */
    public function setConfiguringThemosisTheme($configuringThemosisTheme)
    {
        $this->configuringThemosisTheme = $configuringThemosisTheme;
    }

    /**
     * is installing themosis theme
     *
     * @return bool
     */
    public function isInstallingThemosisTheme()
    {
        return $this->installingThemosisTheme;
    }

    /**
     * set installing themosis theme
     *
     * @param  bool $installingThemosisTheme
     * @return void
     */
    public function setInstallingThemosisTheme($installingThemosisTheme)
    {
        $this->installingThemosisTheme = $installingThemosisTheme;
    }

    /**
     * ask configuration questions
     *
     * @return void
     */
    public function askConfigQuestions()
    {
        $config  = $this->getConfig();
        $io      = $this->getIO();
        $options = $this->getOptions();

        if ($io->isInteractive()) {
            try {
                $environment = Config::validateEnvironment($this->getOption('environment'));
            } catch (InvalidEnvironmentException $e) {
                // get answers to our questions
                $environment = $io->askAndValidate(
                    Helper::formatQuestion('Environment', $config->getEnvironment()),
                    "MVPDesign\ThemosisInstaller\Config::validateEnvironment",
                    false,
                    $config->getEnvironment()
                );
            }

            try {
                $dbName = Helper::validateString($this->getOption('dbName'));
            } catch (InvalidStringLengthException $e) {
                $dbName = $io->askAndValidate(
                    Helper::formatQuestion('Database name', $config->getDbName()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getDbName()
                );
            }

            try {
                $dbUser = Helper::validateString($this->getOption('dbUser'));
            } catch (InvalidStringLengthException $e) {
                $dbUser = $io->askAndValidate(
                    Helper::formatQuestion('Database user', $config->getDbUser()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getDbUser()
                );
            }

            try {
                $dbPassword = Helper::validateString($this->getOption('dbPassword'));
            } catch (InvalidStringLengthException $e) {
                $dbPassword = $io->askAndValidate(
                    Helper::formatQuestion('Database passsword', $config->getDbPassword()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getDbPassword()
                );
            }

            try {
                $dbHost = Helper::validateString($this->getOption('dbHost'));
            } catch (InvalidStringLengthException $e) {
                $dbHost = $io->askAndValidate(
                    Helper::formatQuestion('Database host', $config->getDbHost()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getDbHost()
                );
            }

            try {
                $dbPrefix = Helper::validateString($this->getOption('dbPrefix'));
            } catch (InvalidStringLengthException $e) {
                $dbPrefix = $io->askAndValidate(
                    Helper::formatQuestion('Database prefix', $config->getDbPrefix()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getDbPrefix()
                );
            }

            try {
                $siteUrl = Helper::validateURL($this->getOption('siteUrl'));
            } catch (InvalidURLException $e) {
                $siteUrl = $io->askAndValidate(
                    Helper::formatQuestion('Site URL', $config->getSiteUrl()),
                    "MVPDesign\ThemosisInstaller\Helper::validateURL",
                    false,
                    $config->getSiteUrl()
                );
            }

            try {
                $generatingWordPressSalts = Helper::validateConfirmation($this->getOption('generatingWordPressSalts'));
            } catch (InvalidConfirmationException $e) {
                $generatingWordPressSalts = $io->askAndValidate(
                    Helper::formatQuestion('Generate WordPress Salts', $this->isGeneratingWordPressSalts() ? 'y' : 'n'),
                    "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                    false,
                    $this->isGeneratingWordPressSalts() ? 'y' : 'n'
                );
            }

            try {
                $configuringThemosis = Helper::validateConfirmation($this->getOption('configuringThemosis'));
            } catch (InvalidConfirmationException $e) {
                $configuringThemosis = $io->askAndValidate(
                    Helper::formatQuestion('Configure Themosis', $this->isConfiguringThemosis() ? 'y' : 'n'),
                    "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                    false,
                    $this->isConfiguringThemosis() ? 'y' : 'n'
                );
            }

            try {
                $installingWordPress = Helper::validateConfirmation($this->getOption('installingWordPress'));
            } catch (InvalidConfirmationException $e) {
                $installingWordPress = $io->askAndValidate(
                    Helper::formatQuestion('Install WordPress', $this->isInstallingWordPress() ? 'y' : 'n'),
                    "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                    false,
                    $this->isInstallingWordPress() ? 'y' : 'n'
                );
            }

            try {
                $configuringThemosisTheme = Helper::validateConfirmation($this->getOption('configuringThemosisTheme'));
            } catch (InvalidConfirmationException $e) {
                $configuringThemosisTheme = $io->askAndValidate(
                    Helper::formatQuestion('Configure Themosis Theme', $this->isConfiguringThemosisTheme() ? 'y' : 'n'),
                    "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                    false,
                    $this->isConfiguringThemosisTheme() ? 'y' : 'n'
                );
            }

            try {
                $installingThemosisTheme = Helper::validateConfirmation($this->getOption('installingThemosisTheme'));
            } catch (InvalidConfirmationException $e) {
                $installingThemosisTheme = $io->askAndValidate(
                    Helper::formatQuestion('Install Themosis Theme', $this->isInstallingThemosisTheme() ? 'y' : 'n'),
                    "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                    false,
                    $this->isInstallingThemosisTheme() ? 'y' : 'n'
                );
            }

            // save the answers
            $config->setEnvironment($environment);
            $config->setDbName($dbName);
            $config->setDbUser($dbUser);
            $config->setDbPassword($dbPassword);
            $config->setDbHost($dbHost);
            $config->setDbPrefix($dbPrefix);
            $config->setSiteUrl($siteUrl);

            $this->setGeneratingWordPressSalts($generatingWordPressSalts == 'y' ? true : false);
            $this->setConfiguringThemosis($configuringThemosis == 'y' ? true : false);
            $this->setInstallingWordPress($installingWordPress == 'y' ? true : false);
            $this->setConfiguringThemosisTheme($configuringThemosisTheme == 'y' ? true : false);
            $this->setInstallingThemosisTheme($installingThemosisTheme == 'y' ? true : false);

            // extra questions if configuring themosis OR installing wordpress OR configuring themosis theme
            if ($configuringThemosis == 'y' || $installingWordPress == 'y' || $configuringThemosisTheme == 'y') {
                try {
                    $siteTitle = Helper::validateString($this->getOption('siteTitle'));
                } catch (InvalidStringLengthException $e) {
                    $siteTitle = $io->askAndValidate(
                        Helper::formatQuestion('Site Title', $config->getSiteTitle()),
                        "MVPDesign\ThemosisInstaller\Helper::validateString",
                        false,
                        $config->getSiteTitle()
                    );
                }

                $siteDescription = $this->getOption('siteDescription');
                if ($siteDescription === false) {
                    $siteDescription = $io->ask(
                        Helper::formatQuestion('Site Description', $config->getSiteDescription()),
                        $config->getSiteDescription()
                    );
                }

                // save the answers
                $config->setSiteTitle($siteTitle);
                $config->setSiteDescription($siteDescription);
            }

            // extra questions if installing wordpress
            if ($installingWordPress == 'y') {
                try {
                    $isSitePublic = Helper::validateConfirmation($this->getOption('isSitePublic'));
                } catch (InvalidConfirmationException $e) {
                    $isSitePublic = $io->askAndValidate(
                        Helper::formatQuestion('Site visible to search engines', $config->isSitePublic() ? 'y' : 'n'),
                        "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                        false,
                        $config->isSitePublic() ? 'y' : 'n'
                    );
                }

                try {
                    $adminUser = Helper::validateString($this->getOption('adminUser'));
                } catch (InvalidStringLengthException $e) {
                    $adminUser = $io->askAndValidate(
                        Helper::formatQuestion('Admin User', $config->getAdminUser()),
                        "MVPDesign\ThemosisInstaller\Helper::validateString",
                        false,
                        $config->getAdminUser()
                    );
                }

                try {
                    $adminPassword = Helper::validateString($this->getOption('adminPassword'));
                } catch (InvalidStringLengthException $e) {
                    $adminPassword = $io->askAndValidate(
                        Helper::formatQuestion('Admin Password', $config->getAdminPassword()),
                        "MVPDesign\ThemosisInstaller\Helper::validateString",
                        false,
                        $config->getAdminPassword()
                    );
                }

                try {
                    $adminEmail = Helper::validateEmail($this->getOption('adminEmail'));
                } catch (InvalidEmailException $e) {
                    $adminEmail = $io->askAndValidate(
                        Helper::formatQuestion('Admin Email', $config->getAdminEmail()),
                        "MVPDesign\ThemosisInstaller\Helper::validateEmail",
                        false,
                        $config->getAdminEmail()
                    );
                }

                // save the answers
                $config->setSiteTitle($siteTitle);
                $config->setSiteDescription($siteDescription);
                $config->setIsSitePublic($isSitePublic == 'y' ? true : false);
                $config->setAdminUser($adminUser);
                $config->setAdminPassword($adminPassword);
                $config->setAdminEmail($adminEmail);
            }

            // extra questions if installing themosis theme
            if ($installingThemosisTheme == 'y') {
                try {
                    $theme = Helper::validateString($this->getOption('theme'));
                } catch (InvalidStringLengthException $e) {
                    $theme = $io->askAndValidate(
                        Helper::formatQuestion('Theme', $this->getTheme()),
                        "MVPDesign\ThemosisInstaller\Helper::validateString",
                        false,
                        $this->getTheme()
                    );
                }

                // save the answers
                $this->setTheme($theme);
            }
        }
    }

    /**
     * runs the installation process
     *
     * @return void
     */
    public function install()
    {
        $io = $this->getIO();

        // generate wordpress salts
        if ($this->isGeneratingWordPressSalts()) {
            $this->generateWordPressSalts();
        }

        // create the env.environment.php file
        $this->createEnvironmentFile();

        // update the environment hostnames
        $this->updateEnvironmentHostname();

        // configure themosis
        if ($this->isConfiguringThemosis()) {
            // update the themosis README.md
            $this->updateThemosisReadmeMD();

            // update the themosis composer.json
            $this->updateThemosisComposerJSON();
        }

        // install wordpress
        if ($this->isInstallingWordPress()) {
            // install the wordpress database
            $this->installWordPress();

            // remove the hello world comment
            $this->removeHelloWorldComment();

            // remove the hello world post
            $this->removeHelloWorldPost();

            // update the sample page
            $this->updateSamplePage();

            // change admin user id
            $this->changeAdminUserID();

            // customize wordpress options
            $this->customizeWordPressOptions();

            // update the rewrite rules
            $this->updateRewriteRules();

            // make the wordpress uploads directory writable
            $this->makeWordPressUploadsDirectoryWritable();
        }

        // configure themosis theme
        if ($this->isConfiguringThemosisTheme()) {
            // update the themosis theme style.css
            $this->updateThemosisThemeStyleCSS();

            // update the themosis theme package.json
            $this->updateThemosisThemePackageJSON();

            // update the themosis theme composer.json
            $this->updateThemosisThemeComposerJSON();

            // update the themosis theme bower.json
            $this->updateThemosisThemeBowerJSON();

            // update the acceptance testing yml
            $this->updateAcceptanceTestingYml();

            // update the codeception yml
            $this->updateCodeceptionYml();

            // rename the themosis theme directory
            $this->renameThemosisThemeDirectory();
        }

        // install themosis theme
        if ($this->isInstallingThemosisTheme()) {
            // set the home template
            $this->setHomeTemplate();

            // make the themosis storage directory writable
            $this->makeThemosisThemeStorageDirectoryWritable();

            // install themosis theme node packages
            $this->installThemosisThemeNodePackages();

            // install themosis theme composer dependencies
            $this->installThemosisThemeComposerDependencies();

            // install themosis theme bower components
            $this->installThemosisThemeBowerComponents();

            // deploy themosis theme assets
            $this->deployThemosisThemeAssets();

            // activate the wordpress theme
            $this->activateWordPressTheme();
        }

        $io->write('Themosis installation complete.');
    }

    /**
     * generate wordpress salts
     *
     * @return void
     */
    private function generateWordPressSalts()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        foreach ($config->getSalts() as $saltKey => $saltValue) {
            $config->setSalt($saltKey, Helper::generateRandomString());
        }

        $io->write('Generated WordPress salts.');
    }

    /**
     * create the env.environment.php file
     *
     * @return void
     */
    private function createEnvironmentFile()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the environment file template
        $envTemplateFilePath = $this->getCWD() . ".env.template.php";
        $envTemplate         = file_get_contents($envTemplateFilePath);

        // inject the environment variables
        $envTemplate = str_replace('$ENVIRONMENT', ucfirst(strtolower($config->getEnvironment())), $envTemplate);
        $envTemplate = str_replace('$DB_NAME', $config->getDbName(), $envTemplate);
        $envTemplate = str_replace('$DB_USER', $config->getDbUser(), $envTemplate);
        $envTemplate = str_replace('$DB_PASSWORD', $config->getDbPassword(), $envTemplate);
        $envTemplate = str_replace('$DB_HOST', $config->getDbHost(), $envTemplate);
        $envTemplate = str_replace('$DB_PREFIX', $config->getDbPrefix(), $envTemplate);
        $envTemplate = str_replace('$WP_SITEURL', $config->getSiteUrl(), $envTemplate);
        foreach ($config->getSalts() as $saltKey => $saltValue) {
            $envTemplate = str_replace('$' . $saltKey, $saltValue, $envTemplate);
        }

        // create the environment file
        $envFileName = '.env.' . strtolower($config->getEnvironment()) . '.php';
        file_put_contents($envFileName, $envTemplate, LOCK_EX);

        $io->write('Created the environment file.');
    }

    /**
     * update the environment hostnames
     *
     * @return void
     */
    private function updateEnvironmentHostname()
    {
        $config   = $this->getConfig();
        $io       = $this->getIO();
        $hostname = gethostname();

        // load the environment hostnames file
        $envHostnamesFilePath = $this->getConfigPath() . "/environment.php";

        if (file_exists($envHostnamesFilePath)) {
            $envHostnames         = file_get_contents($envHostnamesFilePath);

            // inject the hostname variables
            $envHostnames = str_replace("'" . strtolower($config->getEnvironment()) . "' => 'machine hostname'", "'" . strtolower($config->getEnvironment()) . "' => '" . $hostname . "'", $envHostnames);

            // update the environment hostnames file
            file_put_contents($envHostnamesFilePath, $envHostnames, LOCK_EX);

            $io->write('Updated the environment hostname.');
        }
    }

    /**
     * update the information in the themosis README.md
     *
     * @return void
     */
    private function updateThemosisReadmeMD()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the README.md file
        $readmeMD = 'README.md';

        if (file_exists($readmeMD)) {
            $md  = $config->getSiteTitle() ."\n";
            $md .= "------------------\n";
            $md .= "\n";
            $md .= "##About\n";
            $md .= "\n";
            $md .= "##Deployments\n";
            $md .= "\n";
            $md .= "##Plugins\n";

            // update the themosis README.md
            file_put_contents($readmeMD, $md, LOCK_EX);

            $io->write('Updated the themosis README.md.');
        }
    }

    /**
     * update the information in the themosis composer.json
     *
     * @return void
     */
    private function updateThemosisComposerJSON()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the composer.json file
        $composerJSON = 'composer.json';

        if (file_exists($composerJSON)) {
            $json = file_get_contents($composerJSON);

            // inject the json variables
            $json = str_replace('"name": "mvpdesign/themosis"', '"name": "' . $config->getSiteSlug() . '"', $json);
            $json = str_replace('"description": "The Themosis framework. A framework for WordPress developers."', '"description": "' . $config->getSiteDescription() . '"', $json);

            // update the themosis composer.json
            file_put_contents($composerJSON, $json, LOCK_EX);

            $io->write('Updated the themosis composer.json.');
        }
    }

    /**
     * install the wordpress database
     *
     * @return void
     */
    private function installWordPress()
    {
        $config = $this->getConfig();

        $siteUrl       = Helper::validateURL($config->getSiteUrl());
        $siteTitle     = Helper::validateString($config->getSiteTitle());
        $adminUser     = Helper::validateString($config->getAdminUser());
        $adminPassword = Helper::validateString($config->getAdminPassword());
        $adminEmail    = Helper::validateEmail($config->getAdminEmail());

        $command  = $this->getBinDirectory() . 'wp core install';
        $command .= ' --url="' . $siteUrl . '"';
        $command .= ' --title="' . $siteTitle . '"';
        $command .= ' --admin_user="' . $adminUser . '"';
        $command .= ' --admin_password="' . $adminPassword . '"';
        $command .= ' --admin_email="' . $adminEmail . '"';

        $this->runProcess($command, 'WordPress installed successfully.');
    }

   /**
     * remove hello world comment
     *
     * @return void
     */
    private function removeHelloWorldComment()
    {
        $commentID = 1;

        $command  = $this->getBinDirectory() . 'wp comment delete ' . $commentID;
        $command .= ' --force';

        $this->runProcess($command, 'Removed hello world WordPress comment.', false, true);
    }

    /**
     * remove hello world post
     *
     * @return void
     */
    private function removeHelloWorldPost()
    {
        $postID = 1;

        $command  = $this->getBinDirectory() . 'wp post delete ' . $postID;
        $command .= ' --force';

        $this->runProcess($command, 'Removed hello world WordPress post.', false, true);
    }

    /**
     * update sample page
     *
     * @return void
     */
    private function updateSamplePage()
    {
        $postID      = 2;
        $postTitle   = 'Home';
        $postContent = '\\';

        $command  = $this->getBinDirectory() . "wp post update " . $postID;
        $command .= " --post_title='" . $postTitle . "'";
        $command .= " --post_name='" . str_replace(' ', '-', strtolower($postTitle)) . "'";
        $command .= " --post_content='" . $postContent . "'";

        $this->runProcess($command, 'Refactored the sample WordPress page.', false, true);
    }

    /**
     * change admin user id
     *
     * @return void
     */
    private function changeAdminUserID()
    {
        $config     = $this->getConfig();
        $oldAdminID = 1;
        $newAdminID = 2;

        $command  = $this->getBinDirectory() . 'wp db query "';
        $command .= 'UPDATE ' . $config->getDbPrefix() . 'users SET ID=' . $newAdminID . ' WHERE ID=' . $oldAdminID . '; ';
        $command .= 'UPDATE ' . $config->getDbPrefix() . 'usermeta SET user_id=' . $newAdminID . ' WHERE user_id=' . $oldAdminID . '; ';
        $command .= 'UPDATE ' . $config->getDbPrefix() . 'posts SET post_author=' . $newAdminID . ' WHERE post_author=' . $oldAdminID . '"';

        $this->runProcess($command, 'Changed admin user ID.', false, true);
    }

    /**
     * customize wordpress options
     *
     * @return void
     */
    private function customizeWordPressOptions()
    {
        $config  = $this->getConfig();
        $options = array();
        $command = $this->getBinDirectory() . 'wp option update';

        // add the options to update
        $options['blogdescription'] = $config->getSiteDescription();
        $options['blog_public']     = $config->isSitePublic() ? 1 : 0;
        $options['show_on_front']   = 'page';
        $options['page_on_front']   = 2;

        foreach ($options as $option => $value) {
            $this->runProcess($command . ' ' . $option . ' "' . $value . '"', "Updated WordPress option '" . $option . "' to '" . $value . "'.", false, true);
        }
    }

    /**
     * update rewrite rules
     *
     * @return void
     */
    private function updateRewriteRules()
    {
        $structure    = "/%category%/%postname%/";
        $categoryBase = "/category/";
        $tagBase      = "/tag/";

        $command  = $this->getBinDirectory() . "wp rewrite structure '" . $structure . "'";
        $command .= ' --category-base=' . $categoryBase;
        $command .= ' --tag-base=' . $tagBase;

        $this->runProcess($command, 'Updated the WordPress rewrite structure.', false, true);
    }

    /**
     * change wordpress uploads directory permissions
     *
     * @return void
     */
    private function makeWordPressUploadsDirectoryWritable()
    {
        // generate the theme storage path
        $uploadsPath = 'public/wp-content/uploads';

        // make the uploads directory writable
        $uploadsWritableCommand = 'chmod -R 777 ' . $uploadsPath;

        $this->runProcess($uploadsWritableCommand, 'WordPress uploads is now writable.', false, true);
    }

    /**
     * update the information in the themosis theme style.css
     *
     * @return void
     */
    private function updateThemosisThemeStyleCSS()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the style.css file
        $styleCSS = $this->retrieveThemosisThemePath('style.css');

        if (file_exists($styleCSS)) {
            $style = file_get_contents($styleCSS);

            // inject the style variables
            $style = str_replace("Theme Name: Themosis", "Theme Name: " . $config->getSiteTitle(), $style);
            $style = str_replace("Theme URI: http://framework.themosis.com/", "Theme URI: http://www.mvpdesign.com/", $style);
            $style = str_replace("Author: Julien Lambé", "Author: MVP Marketing + Design", $style);
            $style = str_replace("Author URI: http://www.themosis.com/", "Author URI: http://www.mvpdesign.com/", $style);
            $style = str_replace("Description: Themosis framework theme.", "Description: " . $config->getSiteDescription(), $style);

            // update the themosis style.css
            file_put_contents($styleCSS, $style, LOCK_EX);

            $io->write('Updated the themosis theme style.css.');
        }
    }

    /**
     * update the information in the themosis theme package.json
     *
     * @return void
     */
    private function updateThemosisThemePackageJSON()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the package.json file
        $packageJSON = $this->retrieveThemosisThemePath('package.json');

        if (file_exists($packageJSON)) {
            $json = file_get_contents($packageJSON);

            // inject the json variables
            $json = str_replace("mvpdesign-themosis", $config->getSiteSlug(), $json);
            $json = str_replace("The Themosis framework theme.", $config->getSiteDescription(), $json);

            // update the themosis package.json
            file_put_contents($packageJSON, $json, LOCK_EX);

            $io->write('Updated the themosis theme package.json.');
        }
    }

    /**
     * update the information in the themosis theme composer.json
     *
     * @return void
     */
    private function updateThemosisThemeComposerJSON()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the composer.json file
        $composerJSON = $this->retrieveThemosisThemePath('composer.json');

        if (file_exists($composerJSON)) {
            $json = file_get_contents($composerJSON);

            // inject the json variables
            $json = str_replace("mvpdesign/themosis", $config->getSiteSlug(), $json);
            $json = str_replace("The Themosis framework theme.", $config->getSiteDescription(), $json);

            // update the themosis theme composer.json
            file_put_contents($composerJSON, $json, LOCK_EX);

            $io->write('Updated the themosis theme composer.json.');
        }
    }

    /**
     * update the information in the themosis theme boswer.json
     *
     * @return void
     */
    private function updateThemosisThemeBowerJSON()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the bower.json file
        $bowerJSON = $this->retrieveThemosisThemePath('bower.json');

        if (file_exists($bowerJSON)) {
            $json = file_get_contents($bowerJSON);

            // inject the json variables
            $json = str_replace("mvpdesign/themosis", $config->getSiteSlug(), $json);

            // update the themosis bower.json
            file_put_contents($bowerJSON, $json, LOCK_EX);

            $io->write('Updated the themosis theme bower.json.');
        }
    }

    /**
     * update the information in the themosis theme tests/acceptance.suite.yml
     *
     * @return void
     */
    private function updateAcceptanceTestingYml()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the tests/acceptance.suite.yml file
        $acceptanceYml = $this->retrieveThemosisThemePath('tests/acceptance.suite.yml');

        if (file_exists($acceptanceYml)) {
            $yml = file_get_contents($acceptanceYml);

            // inject the yml variables
            $yml = str_replace("url: 'http://localhost'", "url: '" . $config->getSiteUrl() . "'", $yml);

            // update the themosis tests/acceptance.suite.yml
            file_put_contents($acceptanceYml, $yml, LOCK_EX);

            $io->write('Updated the themosis theme tests/acceptance.suite.yml.');
        }
    }

    /**
     * update the information in the themosis theme codeception.yml
     *
     * @return void
     */
    private function updateCodeceptionYml()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        // load the codeception.yml file
        $codeceptionYml = $this->retrieveThemosisThemePath('codeception.yml');

        if (file_exists($codeceptionYml)) {
            $yml = file_get_contents($codeceptionYml);

            // inject the yml variables
            $yml = str_replace("dsn: ''", "dsn: 'mysql:host=" . $config->getDbHost() . ";dbname=" . $config->getDbName() . "_test'", $yml);
            $yml = str_replace("user: ''", "user: '" . $config->getDbUser() . "'", $yml);
            $yml = str_replace("password: ''", "password: '" . $config->getDbPassword() . "'", $yml);

            // update the themosis codeception.yml
            file_put_contents($codeceptionYml, $yml, LOCK_EX);

            $io->write('Updated the themosis theme codeception.yml.');
        }
    }

    /**
     * rename themosis theme directory
     *
     * @return void
     */
    private function renameThemosisThemeDirectory()
    {
        $config = $this->getConfig();

        $command = 'mv ' . $this->retrieveThemosisThemePath() . ' ' . $this->retrieveThemosisThemePath('../' . $config->getSiteSlug());
        $this->setTheme($config->getSiteSlug());

        $this->runProcess($command, 'Renamed themosis theme directory.', false, true);
    }

    /**
     * set the home template
     *
     * @return void
     */
    private function setHomeTemplate()
    {
        $postID = 2;
        $metaKey = '_themosisPageTemplate';
        $metaValue = 'home';

        $command = $this->getBinDirectory() . 'wp post meta set ' . $postID . ' ' . $metaKey . ' ' . $metaValue;

        $this->runProcess($command, "Set the home page to the home themosis template.", false, true);
    }

    /**
     * change themosis theme storage directory permissions
     *
     * @return void
     */
    private function makeThemosisThemeStorageDirectoryWritable()
    {
        // generate the theme storage path
        $storagePath = $this->retrieveThemosisThemePath($this->getStoragePath());

        // make the storage directory writable
        $storageWritableCommand = 'chmod -R 777 ' . $storagePath;

        $this->runProcess($storageWritableCommand, 'Themosis storage directory is now writable.', false, true);
    }

    /**
     * install themosis theme node packages
     *
     * @return void
     */
    private function installThemosisThemeNodePackages()
    {
        $command = 'cd ' . $this->retrieveThemosisThemePath() . ' && npm install';

        $this->runProcess($command, 'Installed node packages.', false, true);
    }

    /**
     * install themosis theme composer dependencies
     *
     * @return void
     */
    private function installThemosisThemeComposerDependencies()
    {
        $command = 'cd ' . $this->retrieveThemosisThemePath() . ' && composer install';

        $this->runProcess($command, 'Installed composer dependencies.', false, true);
    }

    /**
     * install themosis theme bower components
     *
     * @return void
     */
    private function installThemosisThemeBowerComponents()
    {
        $command = 'cd ' . $this->retrieveThemosisThemePath() . ' && bower install';

        $this->runProcess($command, 'Installed bower components.', false, true);
    }

    /**
     * deploy themosis theme assets
     *
     * @return void
     */
    private function deployThemosisThemeAssets()
    {
        $config = $this->getConfig();

        $command = 'cd ' . $this->retrieveThemosisThemePath() . ' && gulp deploy:' . $config->getEnvironment();

        $this->runProcess($command, 'Deployed themosis theme assets.', false, true);
    }

    /**
     * activate the wordpress theme
     *
     * @return void
     */
    private function activateWordPressTheme()
    {
        $config = $this->getConfig();

        $command = $this->getBinDirectory() . 'wp theme activate ' . $this->getTheme();

        $this->runProcess($command, "Activated the '" . $this->getTheme() . "' WordPress theme.", false, true);
    }

    /**
     * retrieve the theme path
     *
     * @return void
     */
    private function retrieveThemosisThemePath($path = '')
    {
        // retrieve the theme path
        $themePathCommand  = $this->getBinDirectory() . 'wp theme path ' . $this->getTheme();

        $themePathCommand .= ' --dir';

        $themePath = $this->runProcess($themePathCommand, '', true) . '/' . $path;

        $themePath = str_replace(array("\n", "\r"), '', $themePath);

        return $themePath;
    }

    /**
     * return the composer bin directory
     *
     * @return void
     */
    private function getBinDirectory()
    {
        $composer       = $this->getComposer();
        $composerConfig = $composer->getConfig();

        return $composerConfig->get('bin-dir') . '/';
    }

    /**
     * return the current working directory
     *
     * @return void
     */
    private function getCWD()
    {
        return dirname(__FILE__) . "/../";
    }

    /**
     * run a process
     *
     * @param  string $command
     * @param  string $successMessage
     * @param  bool $returnOutput
     * @param  bool $quietlyFail
     * @return void
     */
    private function runProcess($command, $successMessage = '', $returnOutput = false, $quietlyFail = false)
    {
        $io = $this->getIO();

        $process = new Process($command);
        $process->setTimeout(3600);
        $process->run();

        if (! $process->isSuccessful()) {
            $errorMessage = $process->getErrorOutput();

            if ($quietlyFail) {
                $io->write($errorMessage);
            } else {
                throw new \RuntimeException($errorMessage);
            }
        }

        $message = $successMessage != '' ? $successMessage : $process->getOutput();

        if ($returnOutput) {
            return $message;
        }

        $io->write($message);
    }
}
