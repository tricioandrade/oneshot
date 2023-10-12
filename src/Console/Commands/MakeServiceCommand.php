<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use OneShot\Builder\Enum\Templates\StubsFilesNameEnum;
use OneShot\Builder\Traits\EssentialsTrait;

class MakeServiceCommand extends Command
{
    use EssentialsTrait;
    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The Console command description.
     *
     * @var string
     */
    protected $description = 'Create services';

    /**
     * Execute the Console command.
     */
    public function handle(): void
    {
        $argument               = $this->argument('name');
        $serviceName            = $argument;
        $servicePath            = app_path().'\\Services';
        $serviceNamespace       = 'App\\Services';
        $baseModelNamespace     = 'App\\Models';
        $serviceTemplateStub    = File::get(base_path('stubs/' . StubsFilesNameEnum::SERVICE->value));

        /**
         * Remove the Controller Suffix if exists,
         * otherwise keeps the original name
         * Ex: MyLovelyController result in  MyLovely
         */
        $fullParamWithoutSuffix         = str_replace('Service', "", $serviceName);
        $serviceNameWithoutSuffixArray  = explode('\\', $fullParamWithoutSuffix);
        $serviceNameWithoutSuffix       = end($serviceNameWithoutSuffixArray);


        /**
         * Set model class name
         */

        $modelClassName = $serviceNameWithoutSuffix . 'Model';

        /**
         * Get service path from given name.
         */
        $baseFilesPath = $servicePath .'\\'. $fullParamWithoutSuffix;

        /**
         * Verify if service name argument has Controller suffix like MyLovelyController
         * if false append it.
         */
        $serviceName = $this->addFileNameSuffix($serviceName, 'Service');

        if (!str_contains($argument, '/'))
        $serviceNamespace = $serviceNamespace . '\\'. $serviceNameWithoutSuffix;

        /**
         * Clear service name if it has / (slash)
         * and update current vars:
         *
         * @var $serviceName
         * @var $baseFilesPath
         * @var $servicePath
         * @var $serviceNamesPace
         *
         */
        if (str_contains($argument, '/')) {
            $argumentArrayResult = explode('/', $argument);
            $serviceName         = $this->addFileNameSuffix(end($argumentArrayResult), 'Service');

            $m = explode('/', $modelClassName);
            $modelClassName      = end($m);

            array_pop($argumentArrayResult);

            $baseFilesPath       = $servicePath .'\\'. implode('\\', $argumentArrayResult);
            $serviceNamespace    = $serviceNamespace .'\\'  . implode('\\', $argumentArrayResult);
            $baseModelNamespace  = $baseModelNamespace. '\\' .  implode('\\', $argumentArrayResult);
        }

        $this->createDir($baseFilesPath);

        $DummyNamespace         = $serviceNamespace;
        $DummyModelPath         = $baseModelNamespace . '\\' . $modelClassName;
        $DummyClass             = $serviceName;
        $DummyModelClass        = $modelClassName;
        $DummyInScopeVariable   = lcfirst($serviceName);

        $target = [
            'DummyNamespace',
            'DummyModelPath',
            'DummyClass',
            'DummyModelClass',
            'DummyInScopeVariable'
        ];

        $newContent =  [
            $DummyNamespace,
            $DummyModelPath,
            $DummyClass,
            $DummyModelClass,
            $DummyInScopeVariable
        ];

        $serviceContent = $this->replaceContent($target, $newContent, $serviceTemplateStub);
        $filePath       = $baseFilesPath .'\\'. $DummyClass . '.php';

        $this->storeContent($filePath, $serviceContent);
        $this->makeCrudTrait($DummyModelPath, $DummyModelClass);

        $this->info("Service ". $serviceName ." created successfully.");
    }


    /**
     * Create CrudTrait repository file
     *
     * @param $dummyModelPath
     * @param $dummyModelClass
     * @return void
     */
    private function makeCrudTrait($dummyModelPath, $dummyModelClass): void
    {
        $crudTemplateStub  = File::get(base_path('stubs/' . StubsFilesNameEnum::CRUD_TRAIT->value));
        $crudTraitFileName = app_path()."\Traits\Essentials\Database\CrudTrait.php";
        $crudTraitFilePath = app_path()."\Traits\Essentials\Database";

        $template = $this->replaceContent(
            [
                'DummyModelClass',
                'DummyModelPath'
            ],
            [
                $dummyModelClass,
                $dummyModelPath
            ],
            $crudTemplateStub
        );

        if (!is_file($crudTraitFileName)){

            $this->createDir($crudTraitFilePath);
            $this->storeContent($crudTraitFileName, $template);
            $this->info("Trait 'CrudTrait' created successfully.");

            Artisan::call('make:exception', ['name' => 'Auth/UnauthorizedException']);
            Artisan::call('make:exception', ['name' => 'DatabaseException']);
            Artisan::call('make:trait', ['name' => 'Common/Auth/VerifyUser']);
        }
    }
}
