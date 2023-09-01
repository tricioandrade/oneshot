<?php

/**
 * Created by Patricio Andrade
 * @github tricioandrade
 * @link https://github.com/tricioandrade
 *
 * LinkedIn
 * @link https://linkedin.com/tricioandrade
 */

namespace Illudir\Builder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeApiControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api-controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controllerName = $this->argument('name');
        $controllerPath = app_path().'\\App\\Http\\Controllers';
        $namespace      = 'App\\Http\\Controllers';
        $serviceStub    = File::get(resource_path('stubs/create.api-controller.stub'));

        $nameWithoutSuffix = $controllerName;
        $baseFilesPath = str_replace('\\', "/", $nameWithoutSuffix);

        if (str_contains($controllerName, '/')) {

            $array          = explode('/', $controllerName);
            $controllerName = end($array);
            $nameWithoutSuffix = $controllerName;

            if (!preg_match('/Controller$/', $controllerName)):
                $controllerName = $controllerName.'Controller';
            else:
                $nameWithoutSuffix = str_replace('Controller', "", $controllerName);
            endif;

            array_pop($array);
            $baseFilesPath  = implode('/', $array);
            $controllerPath = app_path().'\\Http\\Controllers\\'. $baseFilesPath;
            $namespace      = 'App\\Http\\Controllers\\'. implode('\\', $array);

            if (!File::exists($controllerPath)) File::makeDirectory($controllerPath, 0755, true);
        }


        $DummyInScopeVariable       = lcfirst($nameWithoutSuffix);

        $DummyInstanceServiceClass  = lcfirst($nameWithoutSuffix) . 'Service';
        $DummyInstanceRequestClass  = lcfirst($nameWithoutSuffix) . 'Request';

        $DummyResourceClass         = $nameWithoutSuffix . 'Resource';
        $DummyServiceClass          = $nameWithoutSuffix . 'Service';
        $DummyRequestClass          = $nameWithoutSuffix . 'Request';


        $DummyServiceClassPath      = 'App\\Services\\'. str_replace("/","\\",$baseFilesPath) . '\\'  . $nameWithoutSuffix . 'Service';
        $DummyRequestClassPath      = 'App\\Http\\Requests\\'. str_replace("/","\\",$baseFilesPath) . '\\'  .$nameWithoutSuffix . 'Request' ;
        $DummyResourceClassPath     = 'App\\Http\\Resources\\'. str_replace("/","\\",$baseFilesPath) . '\\'  .$nameWithoutSuffix .'Resource';

        $serviceStub = str_replace([
            'DummyClass',
            'DummyNamespace',
            'DummyInScopeVariable',
            'DummyInstanceServiceClass',
            'DummyInstanceRequestClass',
            'DummyResourceClass',
            'DummyServiceClass',
            'DummyRequestClass',
            'DummyServicePath',
            'DummyRequestPath',
            'DummyResourcePath',
        ],  [
            $controllerName,
            $namespace,
            $DummyInScopeVariable,
            $DummyInstanceServiceClass,
            $DummyInstanceRequestClass,
            $DummyResourceClass,
            $DummyServiceClass,
            $DummyRequestClass,
            $DummyServiceClassPath,
            $DummyRequestClassPath,
            $DummyResourceClassPath,
        ], $serviceStub);

        $filePath = $controllerPath. '\\' . $controllerName . '.php';
        File::put($filePath, $serviceStub);

        $this->newLine(1);
        Artisan::call('make:resource',  ['name' => $baseFilesPath .'\\' .$nameWithoutSuffix . 'Resource']);
        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API resource ${controllerName}Resource created successfully.</>");
        $this->newLine(1);

        Artisan::call('make:request',   ['name' => $baseFilesPath .'\\' .$nameWithoutSuffix . 'Request']);
        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API request ${controllerName}Request created successfully.</>");
        $this->newLine();

        Artisan::call('make:model',     ['name' => $baseFilesPath .'\\' .$nameWithoutSuffix . 'Model', '--migration' => true]);
        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API model ${nameWithoutSuffix}Model created successfully.</>");
        $this->newLine();

        Artisan::call('make:service ' . $baseFilesPath .'/' .$nameWithoutSuffix . 'Service');
        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API service ${nameWithoutSuffix}Service created successfully.</>");
        $this->newLine();

        $this->info("\t<fg=white;bg=green>INFO</>\t <fg=white>API controller ${controllerName}Controller created successfully.</>");
        $this->newLine(1);

    }
}
