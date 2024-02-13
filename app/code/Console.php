<?php

/**
 * @copyright Copyright © 2024 Semajo. All rights reserved.
 * @author    Juan Carlos Conde <juancarlosc@onetree.com>
 */

declare(strict_types=1);

namespace Semajo\Project;

use Robo\Robo;
use Robo\Runner;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class Console
{
    private static \Consolidation\Config\ConfigInterface $config;

    /**
     * @return int
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function run(): int
    {
        $input = new ArgvInput();
        $output = new ConsoleOutput();

        $runner = self::init();
        $container = $runner->getContainer();

        // Find all commands
        $commandClassNames = self::getCommandClassNames(dirname(__FILE__), __NAMESPACE__);

        // run the application
        return $runner->run($input, $output, $container->get('application'), $commandClassNames);
    }

    /**
     * @return Runner
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private static function init(): Runner
    {
        // Create symfony application
        self::$config = Robo::createConfiguration(['./../etc/config.yaml']);
        $application = new Application(self::$config->get('appName'), self::$config->get('appVersion'));

        // Create and configure container
        $container = Robo::createContainer($application, self::$config);
        Robo::finalizeContainer($container);

        // Instantiate Robo Runner
        $runner = new Runner();
        $runner->setContainer($container);

        return $runner;
    }

    /**
     * Build an array of PHP class names (including namespaces) for all command classes by recursively running
     * through the subdirectories (of the given directory) and finding all PHP files with a filename ending in
     * "Command.php".
     *
     * @param string $directory
     * @param string $namespace
     * @return array
     */
    private static function getCommandClassNames(string $directory, string $namespace): array
    {
        $commandClassNames = [];
        $directoryHandle = opendir($directory);
        if ($directoryHandle) {
            // Iterate through all files and directories
            while (($filename = readdir($directoryHandle)) !== false) {
                $filepath = $directory . DIRECTORY_SEPARATOR . $filename;
                if (!is_dir($filepath)) {
                    // We found a file. Does its filename end in 'Command.php'?
                    if (str_ends_with($filepath, 'Command.php')) {
                        // Build the class name and store it in the class name array.
                        $pathInfo = pathinfo($filepath);
                        $commandClassNames[] = $namespace . '\\' . $pathInfo['filename'];
                    }
                } elseif ($filename !== '.' && $filename !== '..') {
                    // We found a directory (different from "." and ".."). Recursively get the class names
                    // of the commands found in the new directory and add them to the internal array.
                    $commandClassNames = array_merge(
                        $commandClassNames,
                        self::getCommandClassNames($filepath, $namespace . '\\' . $filename)
                    );
                }
            }
            closedir($directoryHandle);
        }

        return $commandClassNames;
    }
}
