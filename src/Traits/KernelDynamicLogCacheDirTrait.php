<?php

namespace RvltDigital\SymfonyRevoltaBundle\Traits;

use LogicException;
use function property_exists;
use function str_replace;
use function strval;

trait KernelDynamicLogCacheDirTrait
{
    public function getCacheDir()
    {
        if (!property_exists($this, 'environment')) {
            throw new LogicException('This trait must be used on kernel');
        }
        if (!isset($_ENV['CACHE_DIR'])) {
            throw new LogicException('The CACHE_DIR env variable must be defined');
        }
        return $this->replaceProjectDir($_ENV['CACHE_DIR'] . '/' . $this->environment);
    }

    public function getLogDir()
    {
        if (!isset($_ENV['LOG_DIR'])) {
            throw new LogicException('The LOG_DIR env variable must be defined');
        }
        return $this->replaceProjectDir(strval($_ENV['LOG_DIR']));
    }

    private function replaceProjectDir(string $directory): string
    {
        return str_replace('%kernel.project_dir%', __DIR__.'/../../../../..', $directory);
    }
}
