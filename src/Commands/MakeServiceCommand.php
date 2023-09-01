<?php

/**
 * Created by Patricio Andrade
 * @github tricioandrade
 * @link https://github.com/tricioandrade
 *
 * LinkedIn
 * @link https://linkedin.com/tricioandrade
 */


namespace OneShot\Builder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceName = $this->argument('name');
        $servicePath = app_path().'\\Services';
        $namespace   = 'App\\Services';
        $serviceStub = File::get(resource_path('stubs/create.service.stub'));
        $fullNameWithoutSuffix = $serviceName;

        $baseFilesPath = str_replace('\\', "/", $fullNameWithoutSuffix);

        if (str_contains($serviceName, '/')) {

            $array       = explode('/', $serviceName);
            $serviceName = end($array);
            $fullNameWithoutSuffix = $serviceName;

            if (!preg_match('/Service$/', end($array))):
                $serviceName = end($array).'Service';
            else:
                $fullNameWithoutSuffix = str_replace('Service', "", $serviceName);
            endif;

            array_pop($array);

            $baseFilesPath  = implode('/', $array);
            $servicePath = app_path().'\\Services\\'.implode('/',$array);
            $namespace   = 'App\\Services\\'. implode('\\', $array);

            if (!File::exists($servicePath)) {
                File::makeDirectory($servicePath, 0755, true);
            }
        }

        $DummyNamespace         = str_replace('/', "\\" ,$namespace);
        $DummyModelPath         = 'App\\Models\\' . str_replace('/',"\\",$baseFilesPath) . '\\' . $fullNameWithoutSuffix . 'Model';
        $DummyClass             = $serviceName;
        $DummyModelClass        = $fullNameWithoutSuffix . 'Model';
        $DummyInScopeVariable   = lcfirst($fullNameWithoutSuffix);

        $serviceStub = str_replace([
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
        ], $serviceStub);

//        $serviceStub = str_replace([ 'DummyClass','DummyNamespace'], [$serviceName, $namespace], $serviceStub);

        $filePath = $servicePath. '\\' . $serviceName . '.php';
        File::put($filePath, $serviceStub);

        $this->info("Service ${serviceName} created successfully.");
    }
}
