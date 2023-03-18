<?php

namespace RocketLauncherLoggerTakeOff\Services;

use League\Flysystem\Filesystem;
use RocketLauncherLoggerTakeOff\ServiceProvider;

class ProjectManager
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    const PROJECT_FILE = 'composer.json';
    const BUILDER_FILE = 'bin/generator';

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function cleanup() {
        $content = $this->filesystem->read(self::BUILDER_FILE);

        $content = preg_replace('/\n *\\\\' . preg_quote(ServiceProvider::class) . '::class,\n/', '', $content);

        $this->filesystem->update(self::BUILDER_FILE, $content);

        $content = $this->filesystem->read(self::PROJECT_FILE);

        $json = json_decode($content, true);

        if(key_exists('require-dev', $json) && key_exists('crochetfeve0251/rocket-launcher-logger-take-off', $json['require-dev'])) {
            unset($json['require-dev']['crochetfeve0251/rocket-launcher-logger-take-off']);
        }

        $content = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . "\n";

        $this->filesystem->update(self::PROJECT_FILE, $content);
    }

    public function add_library() {
        if( ! $this->filesystem->has(self::PROJECT_FILE)) {
            return false;
        }

        $content = $this->filesystem->read(self::PROJECT_FILE);
        $json = json_decode($content,true);
        if(! $json || ! array_key_exists('require-dev', $json) || ! array_key_exists('extra', $json) || ! array_key_exists('mozart', $json['extra']) || ! array_key_exists('packages', $json['extra']['mozart'])) {
            return false;
        }

        if(! key_exists('crochetfeve0251/rocket-launcher-logger', $json['require-dev'])) {
            $json['require-dev']['crochetfeve0251/rocket-launcher-logger'] = '^0.0.1';
        }

        if(! in_array('berlindb/core', $json['extra']['mozart']['packages'])) {
            $json['extra']['mozart']['packages'][] = 'crochetfeve0251/rocket-launcher-logger';
        }

        $content = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . "\n";
        $this->filesystem->update(self::PROJECT_FILE, $content);

        return true;
    }

}
