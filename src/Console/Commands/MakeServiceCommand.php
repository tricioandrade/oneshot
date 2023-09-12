<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
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
    public function handle()
    {
        $argument               = $this->argument('name');
        $serviceName            = $argument;
        $servicePath            = app_path().'\\Services';
        $serviceNamespace       = 'App\\Services';
        $baseModelNamespace     = 'App\\Models';
        $serviceTemplateStub    = File::get(base_path('stubs/create.service.stub'));

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
        $baseFilesPath = $servicePath . $fullParamWithoutSuffix;

        /**
         * Verify if service name argument has Controller suffix like MyLovelyController
         * if false append it.
         */
        if (!preg_match('/Service$/', $serviceName)) $serviceName = $serviceName.'Service';

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
            $serviceName         = end($argumentArrayResult);

            $m = explode('/', $modelClassName);
            $modelClassName      = end($m);

            array_pop($argumentArrayResult);

            $baseFilesPath       = $servicePath .'\\'. implode('\\', $argumentArrayResult);
            $servicePath         = app_path().'\\Services\\'. $baseFilesPath;
            $serviceNamespace    = $serviceNamespace .'\\'  . implode('\\', $argumentArrayResult);
            $baseModelNamespace  = $baseModelNamespace. '\\' .  implode('\\', $argumentArrayResult);
        }

        if (!File::exists($baseFilesPath)) File::makeDirectory($baseFilesPath, 0755, true);

        $DummyNamespace         = $serviceNamespace;
        $DummyModelPath         = $baseModelNamespace . '\\' . $modelClassName;
        $DummyClass             = $serviceName;
        $DummyModelClass        = $modelClassName;
        $DummyInScopeVariable   = lcfirst($serviceName);

        $serviceContent = str_replace([
            'DummyNamespace',
            'DummyModelPath',
            'DummyClass',
            'DummyModelClass',
            'DummyInScopeVariable'
        ],  [
            $DummyNamespace,
            $DummyModelPath,
            $DummyClass,
            $DummyModelClass,
            $DummyInScopeVariable
        ], $serviceTemplateStub);

        $filePath = $baseFilesPath .'\\'. $DummyClass . '.php';
        File::put($filePath, $serviceContent);
        
        $this->makeCrudTrait($DummyModelClass, $DummyModelPath);

        Artisan::call('make:exception ', ['name' => 'Auth/UnauthorizedException']);
        Artisan::call('make:trait ', ['name' => 'Auth/VerifyUserPrivilegeTrait']);

        $this->info("Service ". $serviceName ." created successfully.");
        $this->info("Trait 'VerifyUserPrivilegeTrait' created successfully.");
        $this->info("Trait 'CrudTrait' created successfully.");

    }


    private function makeCrudTrait($dummyModelPath, $dummyModelClass)
    {
        $crudTemplateStub  = File::get(base_path('stubs/create.crud-trait.stub'));
        $crudTraitFileName = app_path()."\Traits\Essentials\Database\CrudTrait.php";
        $crudTraitFilePath = app_path()."\Traits\Essentials\Database";

        $template = str_replace([
            'DummyModel',
            'DummyModelPath'
        ], [ $dummyModelClass, $dummyModelPath ], file_get_contents($crudTemplateStub));

        if (!is_file($crudTraitFileName)){
            
            if (!File::isDirectory($crudTraitFilePath)) File::makeDirectory($crudTraitFilePath);

            File::put($crudTraitFileName, $template);
        }
    }
}
