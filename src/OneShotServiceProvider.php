<?php

namespace OneShot\Builder;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use OneShot\Builder\Enum\Templates\StubsFilesEnum;


class OneShotServiceProvider extends ServiceProvider
{

    /**
     * Classes registering
     */
    public function register()
    {
        $this->commands([
            \OneShot\Builder\Console\Commands\MakeApiControllerCommand::class,
            \OneShot\Builder\Console\Commands\MakeEnumCommand::class,
            \OneShot\Builder\Console\Commands\MakeServiceCommand::class,
            \OneShot\Builder\Console\Commands\MakeTraitCommand::class,
            \OneShot\Builder\Console\Commands\PublishConfigPackCommand::class
        ]);
    }

    /**
     * Copy default Templates to laravel .\stubs dir.
     */
    public function boot()
    {
        Artisan::call('oneshot:publish');
        $this->copyDefaultTemplates();
    }

    public function copyDefaultTemplates()
    {
        $resourcePath = config('oneshot.path') . '\\' ;
        $defaultStubsPath = __DIR__ . '\\Templates\\Stubs\\';

        if (!File::exists(base_path('stubs'))) File::makeDirectory(base_path('stubs'));

        foreach (StubsFilesEnum::values() as $stub){
            if (!File::exists($resourcePath . $stub)):
                $content = File::get($defaultStubsPath . $stub);
                File::put($resourcePath . $stub, $content);
            endif;
        }
    }
}