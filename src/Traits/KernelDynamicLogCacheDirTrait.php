<?php

namespace RvltDigital\SymfonyRevoltaBundle\Traits;

use LogicException;
use function getenv;
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
        if (!getenv('CACHE_DIR')) {
            throw new LogicException('The CACHE_DIR env variable must be defined');
        }
        return $this->replaceProjectDir(getenv('CACHE_DIR') . '/' . $this->environment);
    }

    public function getLogDir()
    {
        if (!getenv('LOG_DIR')) {
            throw new LogicException('The LOG_DIR env variable must be defined');
        }
        return $this->replaceProjectDir(strval(getenv('LOG_DIR')));
    }

    private function replaceProjectDir(string $directory): string
    {
        return str_replace('%kernel.project_dir%', __DIR__.'/..', $directory);
    }
}
