<?php

namespace Enlighten\Builder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;


class EnlightenServiceProvider extends ServiceProvider
{

    /**
     * Classes registering
     */
    public function register()
    {
        $this->commands([
            \Enlighten\Builder\Commands\MakeApiControllerCommand::class,
            \Enlighten\Builder\Commands\MakeEnumCommand::class,
            \Enlighten\Builder\Commands\MakeServiceCommand::class,
            \Enlighten\Builder\Commands\MakeTraitCommand::class
        ]);
    }

    /**
     */
    public function boot()
    {
        $sourceDirectory = __DIR__ . '/src/stubs';
        $destinationDirectory = resource_path();

        $this->copyDirectory($sourceDirectory, $destinationDirectory);
    }

    /**
    * Copy a directory and his content .
    *
    * @param string $source
    * @param string $destination
    * @return void
    */
    protected function copyDirectory($source, $destination)
    {
        $filesystem = new Filesystem();

        if ($filesystem->isDirectory($source)) {
            $filesystem->copyDirectory($source, $destination);
        }
    }
}