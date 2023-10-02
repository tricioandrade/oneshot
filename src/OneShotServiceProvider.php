<?php

namespace OneShot\Builder;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use OneShot\Builder\Enum\Templates\StubsFilesNameEnum;
use OneShot\Builder\Traits\EssentialsTrait;

class OneShotServiceProvider extends ServiceProvider
{
    use EssentialsTrait;

    /**
     * Classes registering
     */
    public function register(): void
    {
        $this->commands([
            \OneShot\Builder\Console\Commands\MakeApiResourcesCommand::class,
            \OneShot\Builder\Console\Commands\MakeEnumCommand::class,
            \OneShot\Builder\Console\Commands\MakeServiceCommand::class,
            \OneShot\Builder\Console\Commands\MakeTraitCommand::class,
            \OneShot\Builder\Console\Commands\PublishConfigPackCommand::class
        ]);
    }

    /**
     * boot package
     */
    public function boot(): void
    {
        $this->copyDefaultTemplates();
    }

    /**
     * Copy files Templates to stubs folder
     * @return void
     */
    public function copyDefaultTemplates(): void
    {
        $resourcePath       = base_path() . '\stubs' ;
        $defaultStubsPath   = __DIR__ . '\\Templates\\Stubs\\';

        $this->createDir($resourcePath);

        foreach (StubsFilesNameEnum::values() as $fileName){
            $fileNamePath       = $resourcePath .'\\'. $fileName;
            $contentFileName    = $defaultStubsPath .'\\'. $fileName;
            $content            = file_get_contents($contentFileName);

            $this->storeContent($fileNamePath, $content);
        }
    }
}
