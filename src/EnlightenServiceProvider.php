<?php

namespace OneShot\Builder;

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
            \OneShot\Builder\Commands\MakeApiControllerCommand::class,
            \OneShot\Builder\Commands\MakeEnumCommand::class,
            \OneShot\Builder\Commands\MakeServiceCommand::class,
            \OneShot\Builder\Commands\MakeTraitCommand::class
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