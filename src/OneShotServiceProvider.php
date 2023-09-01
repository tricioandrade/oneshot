<?php

namespace OneShot\Builder;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;


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
     */
    public function boot()
    {
        if (!File::exists(resource_path('stubs'))):
            $api = File::get(__DIR__.'stubs/create.api-controller.stub');
            $enum = File::get(__DIR__.'stubs/create.enum.stub');
            $trait = File::get(__DIR__.'stubs/create.trait.stub');
            $service = File::get(__DIR__.'stubs/create.service.stub');

            File::put(resource_path('stubs').'create.api-controller.stub', $api);
            File::put(resource_path('stubs').'create.enum.stub', $enum);
            File::put(resource_path('stubs').'create.trait.stub', $trait);
            File::put(resource_path('stubs').'create.service.stub', $service);
        endif;
    }
}