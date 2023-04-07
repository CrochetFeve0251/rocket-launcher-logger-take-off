<?php

namespace LaunchpadLoggerTakeOff\Commands;

use LaunchpadCLI\Commands\Command;
use LaunchpadCLI\Entities\Configurations;
use LaunchpadLoggerTakeOff\Services\ConfigsManager;

class InstallCommand extends Command
{
    /**
     * @var Configurations
     */
    protected $configurations;

    /**
     * @var ConfigsManager
     */
    protected $configs_manager;

    public function __construct(Configurations $configurations, ConfigsManager $configs_manager)
    {
        parent::__construct('logger:initialize', 'Initialize the logger library');

        $this->configurations = $configurations;
        $this->configs_manager = $configs_manager;

        $this
            // Usage examples:
            ->usage(
            // append details or explanation of given example with ` ## ` so they will be uniformly aligned when shown
                '<bold>  $0 logger:initialize</end> ## Initialize the logger library<eol/>'
            );
    }

    public function execute() {
        $this->configs_manager->set_up_provider($this->configurations);
        $this->configs_manager->set_parameters($this->configurations);
    }
}
