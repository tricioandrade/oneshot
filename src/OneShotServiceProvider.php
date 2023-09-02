<?php

namespace OneShot\Builder;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use OneShot\Builder\Enum\StubsFiles;


class OneShotServiceProvider extends ServiceProvider
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
     * Copy default stubs to laravel resources/stubs dir.
     */
    public function boot()
    {
        $resourcePath = resource_path('stubs') . '\\' ;
        $defaultStubsPath = __DIR__ . '\\stubs\\';

        foreach (StubsFiles::values() as $stub){
            if (!File::exists($resourcePath . $stub)):
                $content = File::get($defaultStubsPath . $stub);
                File::put($resourcePath . $stub, $content);
            endif;
        }
    }
}