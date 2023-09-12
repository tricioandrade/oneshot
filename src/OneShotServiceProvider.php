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
            \OneShot\Builder\Console\Commands\MakeApiResourcesCommand::class,
            \OneShot\Builder\Console\Commands\MakeEnumCommand::class,
            \OneShot\Builder\Console\Commands\MakeServiceCommand::class,
            \OneShot\Builder\Console\Commands\MakeTraitCommand::class,
            \OneShot\Builder\Console\Commands\PublishConfigPackCommand::class
        ]);

<<<<<<< Updated upstream
        Artisan::call('oneshot:publish');

        $this->copyDefaultTemplates();
=======
>>>>>>> Stashed changes
    }

    /**
     * Copy default Templates to laravel .\stubs dir.
     */
    public function boot()
    {
<<<<<<< Updated upstream

=======
        $this->copyDefaultTemplates();
>>>>>>> Stashed changes
    }

    public function copyDefaultTemplates()
    {
<<<<<<< Updated upstream

        try {

            $resourcePath = config('oneshot.path') . '\\' ;
            $defaultStubsPath = __DIR__ . '\\Templates\\Stubs\\';

            if (!File::exists(base_path('stubs'))) {
                File::makeDirectory(base_path('stubs'));

                foreach (StubsFilesEnum::values() as $stub) {

                    if (!empty($resourcePath)) $resourcePath = base_path('stubs');

                    if (!File::exists($resourcePath . $stub)):
                        $content = File::get($defaultStubsPath . $stub);
                        (File::put($resourcePath . $stub, $content));
                    endif;
                }
            }
        }catch (\Throwable $exception){
            print_r($exception->getMessage() . "\n");
=======
        $resourcePath = base_path() . '\stubs' ;
        $defaultStubsPath = __DIR__ . '\\Templates\\Stubs\\';

        if (!File::exists($resourcePath)) File::makeDirectory($resourcePath);

        foreach (StubsFilesEnum::values() as $file){
            if (!File::exists($resourcePath .'\\'. $file)):
                $content = File::get($defaultStubsPath .'\\'. $file);
                File::put($resourcePath .'\\'. $file, $content);
            endif;
>>>>>>> Stashed changes
        }
    }
}
