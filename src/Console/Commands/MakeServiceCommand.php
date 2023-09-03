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
        $serviceTemplateStub    = File::get(base_path('stubs/create.service.stub'));

        /**
         * Remove the Controller Suffix if exists,
         * otherwise keeps the original name
         * Ex: MyLovelyController result in  MyLovely
         */
        $serviceNameWithoutSuffix = explode('/', str_replace('Service', "", $serviceName));
        $serviceNameWithoutSuffix = end($serviceNameWithoutSuffix);

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
        if (str_contains($serviceName, '/')) {
            $argumentArrayResult = explode('/', $serviceName);
            $serviceName         = end($argumentArrayResult);

            array_pop($argumentArrayResult);

            $baseFilesPath       = str_replace('/', "\\", implode('\\', $argumentArrayResult));
            $servicePath         = app_path().'\\Services\\'. $baseFilesPath;
            $controllerNamespace = 'App\\Services\\'. implode('\\', $argumentArrayResult);
            $serviceNamespace    = str_replace('/', "\\" , $serviceNamespace);
        }

        if (!File::exists($servicePath)) File::makeDirectory($servicePath, 0755, true);

        if (!File::exists($servicePath)) {
            File::makeDirectory($servicePath, 0755, true);
        }

        $DummyNamespace         = str_replace('/', "\\" , $serviceNamespace);
        $DummyModelPath         = 'App\\Models\\' . $baseFilesPath . '\\' . $serviceNameWithoutSuffix . 'Model';
        $DummyClass             = $serviceName;
        $DummyModelClass        = $serviceNameWithoutSuffix . 'Model';
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

        $filePath = $servicePath. '\\' . $serviceName . '.php';
        File::put($filePath, $serviceTemplateStub);

        $this->info("Service ${serviceName} created successfully.");
    }
}
