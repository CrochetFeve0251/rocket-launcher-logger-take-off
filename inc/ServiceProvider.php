<?php

namespace RocketLauncherLoggerTakeOff;

use League\Flysystem\Filesystem;
use RocketLauncherBuilder\App;
use RocketLauncherBuilder\Entities\Configurations;
use RocketLauncherBuilder\ServiceProviders\ServiceProviderInterface;
use RocketLauncherLoggerTakeOff\Commands\InstallCommand;
use RocketLauncherLoggerTakeOff\Services\ConfigsManager;
use RocketLauncherLoggerTakeOff\Services\ProjectManager;

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
        $project_manager = new ProjectManager($this->filesystem);
        $configs_manager = new ConfigsManager($this->filesystem);
        $app->add(new InstallCommand($this->configs, $project_manager, $configs_manager));

        return $app;
    }
}
