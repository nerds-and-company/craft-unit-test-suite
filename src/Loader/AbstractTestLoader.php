<?php

namespace UnitTestSuite\Loader;

use Symfony\Component\Yaml\Yaml;

use Craft\Craft as Craft;
use Craft\IOHelper;
use Craft\LogLevel;

/**
 * Class AbstractTestLoader
 * @package UnitTestSuite\Loader
 */
class AbstractTestLoader
{
    const UNITTESTSUITE_CONFIG = './craft/config/unittestsuite.yml';

    /**
     * @var array
     */
    protected $config;


    /**
     * AbstractTestLoader constructor.
     */
    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Loads config file
     */
    private function loadConfig()
    {
        try {
            if ($yaml = IOHelper::getFileContents(self::UNITTESTSUITE_CONFIG)) {
                $this->config = Yaml::parse($yaml);
            } else {
                $this->logError(sprintf('Unable to load configuration from %s', self::UNITTESTSUITE_CONFIG));
            }
        } catch (\Exception $e) {
            $this->logError(sprintf('An error occurred loading configuration: %s', $e->getMessage()));
        }
    }

    /**
     * Logs error
     * @param $message
     */
    private function logError($message) {
        Craft::log($message, LogLevel::Error);
    }

    /**
     * @return array
     */
    private function getFiles()
    {
        return (isset($this->config['files']) && is_array($this->config['files']))
            ? $this->config['files']
            : array();
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getPluginRoot()
    {
        return \Craft\craft()->path->getPluginsPath();
    }

    /**
     * Loads configured files
     */
    public function requireFiles()
    {
        $pluginRoot = $this->getPluginRoot();
        foreach ($this->getFiles() as $file) {
            require_once $pluginRoot . $file;
        }
    }
}
