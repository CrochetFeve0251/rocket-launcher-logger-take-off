<?php

namespace RocketLauncherLoggerTakeOff\Services;

use League\Flysystem\Filesystem;
use RocketLauncherBuilder\Entities\Configurations;
use RocketLauncherLogger\MonologHandler;
use RocketLauncherLogger\ServiceProvider;

class ConfigsManager
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function set_up_provider(Configurations $configurations) {
        $base_namespace = $configurations->getBaseNamespace();
        $providers_path = 'configs/providers.php';

        if ( ! $this->filesystem->has( $providers_path ) ) {
            return;
        }

        $content = $this->filesystem->read( $providers_path );

        if(! preg_match('/(?<array>return\s\[(?<content>(?:[^[\]]+|(?R))*)\]\s*;\s*$)/', $content, $results)) {
            return;
        }

        $result_content = $results['content'];
        $result_content = "\n    \\$base_namespace" . "Dependencies\\" . ServiceProvider::class . "::class," . $result_content;
        $content = str_replace($results['content'], $result_content, $content);

        $this->filesystem->update($providers_path, $content);
    }


    public function set_parameters(Configurations $configurations) {
        $base_namespace = $configurations->getBaseNamespace();

        $params_path = 'configs/parameters.php';

        if ( ! $this->filesystem->has( $params_path ) ) {
            return;
        }

        $content = $this->filesystem->read( $params_path );

        if( ! preg_match('/[\'"]translation_key[\'"]\s=>\s*[\'"](?<key>[^\'"]*)[\'"]/', $content, $results)) {
            return;
        }

        $name = $results['key'];

        if(! preg_match('/(?<array>return\s\[(?:[^[\]]+|(?R))*\]\s*;\s*$)/', $content, $results)) {
            return;
        }

        $array = $results['array'];

        if(! preg_match('/(?<indents>\h*)[\'"].*[\'"]\s=>/', $array, $results)) {
            return;
        }

        $indents = $results['indents'];
        $new_content = "$indents'log_enabled' => false,\n";
        $new_content .= "$indents'log_handlers' => [\n$indents    \\$base_namespace\\Dependencies\\" . MonologHandler::class . "::class\n$indents],\n";
        $new_content .= "$indents'logger_name' => '$name',\n";
        $new_content .= "$indents'log_file_name' => '$name.log',\n";
        $new_content .= "$indents'log_path' => '',\n";
        $new_content .= "$indents'log_debug_interval' => 0,\n";
        $new_content .= "];\n";

        $content = preg_replace('/]\s*;\s*$/', $new_content, $content);

        $this->filesystem->update($params_path, $content);
    }
}
