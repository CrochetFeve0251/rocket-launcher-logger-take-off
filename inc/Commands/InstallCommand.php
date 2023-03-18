<?php

namespace RocketLauncherLoggerTakeOff\Commands;

use RocketLauncherBuilder\Commands\Command;
use RocketLauncherLoggerTakeOff\Services\ConfigsManager;
use RocketLauncherLoggerTakeOff\Services\ProjectManager;

class InstallCommand extends Command
{

    /**
     * @var ProjectManager
     */
    protected $project_manager;

    /**
     * @var ConfigsManager
     */
    protected $configs_manager;

    public function __construct(ProjectManager $project_manager, ConfigsManager $configs_manager)
    {
        parent::__construct('logger:initialize', 'Initialize the logger library');

        $this->project_manager = $project_manager;
        $this->configs_manager = $configs_manager;

        $this
            // Usage examples:
            ->usage(
            // append details or explanation of given example with ` ## ` so they will be uniformly aligned when shown
                '<bold>  $0 logger:initialize</end> ## Initialize the logger library<eol/>'
            );
    }

    public function execute() {
        $this->project_manager->add_library();
        $this->configs_manager->set_up_provider();
        $this->configs_manager->set_parameters();
        $this->project_manager->cleanup();
        $this->project_manager->reload();
    }
}
