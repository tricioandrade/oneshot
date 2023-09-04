<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
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
        $serviceName            = $this->argument('name');
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

        print_r($serviceNameWithoutSuffixArray);

        /**
         * Set model class name
         */

        $modelClassName         = $serviceNameWithoutSuffix . 'Model';

        /**
         * Get service path from given name.
         */
        $baseFilesPath = str_replace('/', "\\", $serviceNameWithoutSuffix);

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
         * @var $serviceName
         * @var $baseFilesPath
         * @var $servicePath
         * @var $serviceNamesPace
         *
         */
        if (str_contains($serviceName, '/') || str_contains($serviceName, '\\')) {
            $argumentArrayResult = explode('/', $serviceName);
            $argumentArrayResult = explode('\\', $serviceName);
            $serviceName         = end($argumentArrayResult);

            array_pop($argumentArrayResult);

            $baseFilesPath       = implode('/', $argumentArrayResult);
            $servicePath         = app_path().'\\Services\\'. $baseFilesPath;
            $serviceNamespace    = $serviceNamespace .'\\'  . implode('\\', $argumentArrayResult);
            $baseModelNamespace  = $baseModelNamespace. '\\' .  implode('\\', $argumentArrayResult);
        }

        if (!File::exists($servicePath)) File::makeDirectory($servicePath, 0755, true);

        if (!File::exists($servicePath)) {
            File::makeDirectory($servicePath, 0755, true);
        }

        $DummyNamespace         = $serviceNamespace;
        $DummyModelPath         = $baseModelNamespace . '\\' . $modelClassName;
        $DummyClass             = $serviceName;
        $DummyModelClass        = $modelClassName;
        $DummyInScopeVariable   = lcfirst($serviceNameWithoutSuffix);

        $serviceTemplateStub = str_replace([
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

        $filePath = $servicePath. '\\' . $DummyClass . '.php';
        File::put($filePath, $serviceTemplateStub);

        $this->info("Service ${serviceName} created successfully.");
    }
}
