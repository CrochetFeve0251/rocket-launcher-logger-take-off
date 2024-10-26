<?php

namespace LaunchpadLoggerTakeOff;

use League\Flysystem\Filesystem;
use LaunchpadCLI\App;
use LaunchpadCLI\Entities\Configurations;
use LaunchpadCLI\ServiceProviders\ServiceProviderInterface;
use LaunchpadLoggerTakeOff\Commands\InstallCommand;
use LaunchpadLoggerTakeOff\Services\ConfigsManager;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Interacts with the filesystem.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Configuration from the project.
     *
     * @var Configurations
     */
    protected $configs;


    /**
     * Instantiate the class.
     *
     * @param Configurations $configs configuration from the project.
     * @param Filesystem $filesystem Interacts with the filesystem.
     * @param string $app_dir base directory from the cli.
     */
    public function __construct(Configurations $configs, Filesystem $filesystem, string $app_dir)
    {
        $this->configs = $configs;
        $this->filesystem = $filesystem;
    }
    public function attach_commands(App $app): App
    {
        $configs_manager = new ConfigsManager($this->filesystem);
        $app->add(new InstallCommand($this->configs, $configs_manager));

        return $app;
    }
}
