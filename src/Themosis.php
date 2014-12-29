<?php

namespace MVPDesign\ThemosisInstaller;

use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Composer;
use Symfony\Component\Process\Process;
use MVPDesign\ThemosisInstaller\Config;
use MVPDesign\ThemosisInstaller\Helper;

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
     * install wordpress
     *
     * @var bool
     */
    private $installingWordPress = true;

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
     * ask configuration questions
     *
     * @return void
     */
    public function askConfigQuestions()
    {
        $config = $this->getConfig();
        $io     = $this->getIO();

        if ($io->isInteractive()) {
            // get answers to our questions
            $dbName = $io->askAndValidate(
                Helper::formatQuestion('Database name', $config->getDbName()),
                "MVPDesign\ThemosisInstaller\Helper::validateString",
                false,
                $config->getDbName()
            );

            $dbUser = $io->askAndValidate(
                Helper::formatQuestion('Database user', $config->getDbUser()),
                "MVPDesign\ThemosisInstaller\Helper::validateString",
                false,
                $config->getDbUser()
            );

            $dbPassword = $io->askAndValidate(
                Helper::formatQuestion('Database passsword', $config->getDbPassword()),
                "MVPDesign\ThemosisInstaller\Helper::validateString",
                false,
                $config->getDbPassword()
            );

            $dbHost = $io->askAndValidate(
                Helper::formatQuestion('Database host', $config->getDbHost()),
                "MVPDesign\ThemosisInstaller\Helper::validateString",
                false,
                $config->getDbHost()
            );

            $environment = $io->askAndValidate(
                Helper::formatQuestion('Environment', $config->getEnvironment()),
                "MVPDesign\ThemosisInstaller\Config::validateEnvironment",
                false,
                $config->getEnvironment()
            );

            $siteUrl = $io->askAndValidate(
                Helper::formatQuestion('Site URL', $config->getSiteUrl()),
                "MVPDesign\ThemosisInstaller\Helper::validateURL",
                false,
                $config->getSiteUrl()
            );

            $generatingWordPressSalts = $io->askAndValidate(
                Helper::formatQuestion('Generate WordPress Salts', $this->isGeneratingWordPressSalts() ? 'y' : 'n'),
                "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                false,
                $this->isGeneratingWordPressSalts() ? 'y' : 'n'
            );

            $installingWordpress = $io->askAndValidate(
                Helper::formatQuestion('Install WordPress', $this->isInstallingWordPress() ? 'y' : 'n'),
                "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                false,
                $this->isInstallingWordPress() ? 'y' : 'n'
            );

            // save the answers
            $config->setDbName($dbName);
            $config->setDbUser($dbUser);
            $config->setDbPassword($dbPassword);
            $config->setDbHost($dbHost);
            $config->setSiteUrl($siteUrl);
            $config->setEnvironment($environment);

            $this->setGeneratingWordPressSalts($generatingWordPressSalts == 'y' ? true : false);
            $this->setInstallingWordpress($installingWordpress == 'y' ? true : false);

            // extra questions if installing wordpress
            if ($installingWordpress == 'y') {
                $siteTitle = $io->askAndValidate(
                    Helper::formatQuestion('Site Title', $config->getSiteTitle()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getSiteTitle()
                );

                $siteDescription = $io->ask(
                    Helper::formatQuestion('Site Description', $config->getSiteDescription()),
                    $config->getSiteDescription()
                );

                $isSitePublic = $io->askAndValidate(
                    Helper::formatQuestion('Site visible to search engines', $config->isSitePublic() ? 'y' : 'n'),
                    "MVPDesign\ThemosisInstaller\Helper::validateConfirmation",
                    false,
                    $config->isSitePublic() ? 'y' : 'n'
                );

                $adminUser = $io->askAndValidate(
                    Helper::formatQuestion('Admin User', $config->getAdminUser()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getAdminUser()
                );

                $adminPassword = $io->askAndValidate(
                    Helper::formatQuestion('Admin Password', $config->getAdminPassword()),
                    "MVPDesign\ThemosisInstaller\Helper::validateString",
                    false,
                    $config->getAdminPassword()
                );

                $adminEmail = $io->askAndValidate(
                    Helper::formatQuestion('Admin Email', $config->getAdminEmail()),
                    "MVPDesign\ThemosisInstaller\Helper::validateEmail",
                    false,
                    $config->getAdminEmail()
                );

                // save the answers
                $config->setSiteTitle($siteTitle);
                $config->setSiteDescription($siteDescription);
                $config->setIsSitePublic($isSitePublic == 'y' ? true : false);
                $config->setAdminUser($adminUser);
                $config->setAdminPassword($adminPassword);
                $config->setAdminEmail($adminEmail);
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

        if ($this->isInstallingWordpress()) {
            // install the wordpress database
            $this->installWordpress();

            // activate the wordpress theme
            $this->activateWordPressTheme();

            // make the themosis storage directory writable
            $this->makeThemosisThemeStorageDirectoryWritable();

            // update the themosis theme style.css
            $this->updateThemosisThemeStyleCSS();

            // remove the hello world comment
            $this->removeHelloWorldComment();

            // remove the hello world post
            $this->removeHelloWorldPost();

            // update the sample page
            $this->updateSamplePage();

            // customize wordpress options
            $this->customizeWordPressOptions();

            // update the rewrite rules
            $this->updateRewriteRules();
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
        $command .= ' --url=' . $siteUrl;
        $command .= ' --title=' . $siteTitle;
        $command .= ' --admin_user=' . $adminUser;
        $command .= ' --admin_password=' . $adminPassword;
        $command .= ' --admin_email=' . $adminEmail;

        $this->runProcess($command, 'WordPress installed successfully.');
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
            $this->runProcess($command . ' ' . $option . ' ' . $value, "Updated WordPress option '" . $option . "' to '" . $value . "'.", false, true);
        }
    }

    /**
     * activate the wordpress theme
     *
     * @return void
     */
    private function activateWordPressTheme()
    {
        $command = $this->getBinDirectory() . 'wp theme activate ' . $this->getTheme();

        $this->runProcess($command, "Activated the '" . ucfirst($this->getTheme()) . "' WordPress theme.", false, true);
    }

    /**
     * retrieve the theme path
     *
     * @return void
     */
    private function retrieveThemosisThemePath()
    {
        // retrieve the theme path
        $themePathCommand  = $this->getBinDirectory() . 'wp theme path ' . $this->getTheme();
        $themePathCommand .= ' --dir';

        return $this->runProcess($themePathCommand, '', true);
    }

    /**
     * change themosis theme storage directory permissions
     *
     * @return void
     */
    private function makeThemosisThemeStorageDirectoryWritable()
    {
        // generate the theme storage path
        $storagePath = $this->retrieveThemosisThemePath() . '/' . $this->getStoragePath();
        $storagePath = str_replace(array("\n", "\r"), '', $storagePath);

        // make the storage directory writable
        $storageWritableCommand = 'chmod -R 777 ' . $storagePath;

        $this->runProcess($storageWritableCommand, 'Themosis storage directory is now writable.', false, true);
    }

    /**
     * update the information in the themosis theme style.css
     *
     * @return void
     */
    private function updateThemosisThemeStyleCSS()
    {
        $config = $this->getConfig();

        // load the style.css file
        $styleCSS = $this->retrieveThemosisThemePath() . '/' . 'style.css';

        if (file_exists($styleCSS)) {
            $style = file_get_contents($styleCSS);

            // inject the style variables
            $style = str_replace("Theme Name: Themosis", "Theme Name: " . $config->getSiteTitle(), $style);
            $style = str_replace("Theme URI: http://framework.themosis.com/", "Theme URI: http://www.mvpdesign.com/", $style);
            $style = str_replace("Author: Julien Lambé", "Author: MVP Marketing + Design", $style);
            $style = str_replace("Description: Themosis framework theme.", "Description: " . $config->getSiteDescription(), $style);

            // update the themosis style.css
            file_put_contents($styleCSS, $style, LOCK_EX);

            $io->write('Updated the themosis theme style.css.');
        }
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