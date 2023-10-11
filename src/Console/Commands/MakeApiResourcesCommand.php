<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use OneShot\Builder\Enum\Templates\StubsFilesNameEnum;
use OneShot\Builder\Traits\EssentialsTrait;

class MakeApiResourcesCommand extends Command
{
    use EssentialsTrait;
    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'make:api-resources {name}';

    /**
     * The Console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the Console command.
     */
    public function handle()
    {
        $controllerName         = $this->argument('name');
        $controllerNamespace    = 'App\\Http\\Controllers';
        $controllerPath         = app_path().'\\'.'Http\\Controllers';
        $controllerTemplateStub = File::get(base_path('stubs/' . StubsFilesNameEnum::API_RESOURCES->value));

        /**
         * Remove the Controller Suffix if exists,
         * otherwise keeps the original name
         * Ex: MyLovelyController result in  MyLovely
         */
        $toArray = explode('/', $this->replaceContent('Controller', "", $controllerName));
        $controllerNameWithoutSuffix = end($toArray);

        /**
         * Get controller path from given name.
         */
        $baseFilesPath = $this->replaceContent('/', "\\", $controllerNameWithoutSuffix);

        /**
         * Verify if controller name argument has Controller suffix like MyLovelyController
         * if false append it.
         */

        $controllerName = $this->addFileNameSuffix($controllerName, 'Controller');

        /**
         * Clear controller name if it has /
         * and update current vars:
         *
         * @var $controllerName
         * @var $controllerName
         * @var $baseFilesPath
         * @var $controllerPath
         * @var $controllerNamesPace
         *
         */
        if (str_contains($controllerName, '/')) {
            $argumentArrayResult    = explode('/', $controllerName);
            $controllerName         = end($argumentArrayResult);

            array_pop($argumentArrayResult);

            $baseFilesPath      = $this->replaceContent('/', "\\", implode('\\', $argumentArrayResult));
            $controllerPath     = app_path().'\\Http\\Controllers\\'. $baseFilesPath;
            $controllerNamespace = 'App\\Http\\Controllers\\'. implode('\\', $argumentArrayResult);
        }

        $this->createDir($controllerPath);

        $c = lcfirst($controllerNameWithoutSuffix);
        $DummyInScopeVariable       = $c;
        $DummyInstanceServiceClass  = $c . 'Service';
        $DummyInstanceRequestClass  = $c . 'Request';

        $DummyResourceClass         = $controllerNameWithoutSuffix . 'Resource';
        $DummyServiceClass          = $controllerNameWithoutSuffix . 'Service';
        $DummyRequestClass          = $controllerNameWithoutSuffix . 'Request';

        $r = $this->replaceContent("/","\\",$baseFilesPath) . '\\' . $controllerNameWithoutSuffix;
        $DummyServiceClassPath      = 'App\\Services\\'. $r . 'Service';
        $DummyRequestClassPath      = 'App\\Http\\Requests\\'. $r . 'Request' ;
        $DummyResourceClassPath     = 'App\\Http\\Resources\\'. $r .'Resource';

        $controllerTemplateStub = $this->replaceContent([
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
            $controllerNamespace,
            $DummyInScopeVariable,
            $DummyInstanceServiceClass,
            $DummyInstanceRequestClass,
            $DummyResourceClass,
            $DummyServiceClass,
            $DummyRequestClass,
            $DummyServiceClassPath,
            $DummyRequestClassPath,
            $DummyResourceClassPath,
        ], $controllerTemplateStub);

        $filePath = $controllerPath. '\\' . $controllerName . '.php';

        $this->storeContent($filePath, $controllerTemplateStub);

        $controllerMessage = "\t<fg=white;bg=green>INFO</>\t <fg=white>API controller "  . $controllerNameWithoutSuffix . " Controller created successfully.</>";
        $this->info($controllerMessage);
        $this->newLine();

        $resourcesFilesBaseName = $baseFilesPath .'\\' .$controllerNameWithoutSuffix;

        $resourceName = $resourcesFilesBaseName . 'Resource';
        $requestName  = $resourcesFilesBaseName . 'Request';
        $modelName    = $resourcesFilesBaseName . 'Model';
        $serviceName  = $baseFilesPath .'/' .$controllerNameWithoutSuffix . 'Service';

        $this->createService($serviceName, $controllerNameWithoutSuffix);
        $this->createRequest($requestName, $controllerNameWithoutSuffix);
        $this->createResource($resourceName, $controllerNameWithoutSuffix);
        $this->createModelAndMigration($modelName, $controllerNameWithoutSuffix);
    }

    /**
     * Create Resource command
     *
     * @param $resourceName
     * @param $n
     * @return void
     */
    public function createResource($resourceName, $n): void
    {
        $m = "\t<fg=white;bg=green>INFO</>\t <fg=white>API resource ". $n. "Resource created successfully.</>";

        Artisan::call('make:resource', ['name' => $resourceName]);

        $this->line($m);
        $this->newLine();
    }

    /**
     * Create Request command
     *
     * @param $requestName
     * @param $n
     */
    public function createRequest($requestName, $n): void
    {
        $m = "\t<fg=white;bg=green>INFO</>\t <fg=white>API request "  . $n . " Request created successfully.</>";

        Artisan::call('make:request', [ 'name' => $requestName]);

        $this->line($m);
        $this->newLine();
    }

    /**
     * Create Model and Migration command
     *
     * @param $modelName
     * @param $n
     * @return void
     */
    public function createModelAndMigration($modelName, $n): void
    {
        $m = "\t<fg=white;bg=green>INFO</>\t <fg=white>API model "  . $n . " Model created successfully.</>";

        Artisan::call('make:model', ['name' => $modelName, '--migration'   => true]);

        $this->line($m);
        $this->newLine();
    }

    /**
     * Create Service command
     *
     * @param $serviceName
     * @return void
     */
    public function createService($serviceName, $n): void
    {
        $m = "\t<fg=white;bg=green>INFO</>\t <fg=white>API service "  . $n . " Service created successfully.</>";

        Artisan::call('make:service', ['name' => $serviceName]);

        $this->line($m);
        $this->newLine();
    }
}
