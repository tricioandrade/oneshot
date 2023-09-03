<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeApiControllerCommand extends Command
{
    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'make:api-controller {name}';

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
        $controllerTemplateStub = File::get(base_path('stubs/create.api-controller.stub'));

        /**
         * Remove the Controller Suffix if exists,
         * otherwise keeps the original name
         * Ex: MyLovelyController result in  MyLovely
         */
        $controllerNameWithoutSuffix = explode('/', str_replace('Controller', "", $controllerName));
        $controllerNameWithoutSuffix = end($controllerNameWithoutSuffix);

        /**
         * Get controller path from given name.
         */
        $baseFilesPath = str_replace('/', "\\", $controllerNameWithoutSuffix);

        /**
         * Verify if controller name argument has Controller suffix like MyLovelyController
         * if false append it.
         */
        if (!preg_match('/Controller$/', $controllerName)) $controllerName = $controllerName.'Controller';

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

            $controllerPath     = app_path().'\\Http\\Controllers\\'. $baseFilesPath;
            $controllerNamespace = 'App\\Http\\Controllers\\'. implode('\\', $argumentArrayResult);
        }

        if (!File::exists($controllerPath)) File::makeDirectory($controllerPath, 0755, true);

        $DummyInScopeVariable       = lcfirst($controllerNameWithoutSuffix);
        $DummyInstanceServiceClass  = lcfirst($controllerNameWithoutSuffix) . 'Service';
        $DummyInstanceRequestClass  = lcfirst($controllerNameWithoutSuffix) . 'Request';

        $DummyResourceClass         = $controllerNameWithoutSuffix . 'Resource';
        $DummyServiceClass          = $controllerNameWithoutSuffix . 'Service';
        $DummyRequestClass          = $controllerNameWithoutSuffix . 'Request';

        $DummyServiceClassPath      = 'App\\Services\\'. str_replace("/","\\",$baseFilesPath) . '\\'  . $controllerNameWithoutSuffix . 'Service';
        $DummyRequestClassPath      = 'App\\Http\\Requests\\'. str_replace("/","\\",$baseFilesPath) . '\\'  .$controllerNameWithoutSuffix . 'Request' ;
        $DummyResourceClassPath     = 'App\\Http\\Resources\\'. str_replace("/","\\",$baseFilesPath) . '\\'  .$controllerNameWithoutSuffix .'Resource';

        $controllerTemplateStub = str_replace([
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
        File::put($filePath, $controllerTemplateStub);

        /**
         * Execute and print artisan tasks message.
         */

        $this->newLine(); // break line.

        // Create new resource
        Artisan::call('make:resource', ['name' => $baseFilesPath .'\\' .$controllerNameWithoutSuffix . 'Resource']);
        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API resource ${controllerNameWithoutSuffix}Resource created successfully.</>");
        $this->newLine();

        // Create new request
        Artisan::call('make:request', [ 'name' => $baseFilesPath .'\\' .$controllerNameWithoutSuffix . 'Request']);
        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API request ${controllerNameWithoutSuffix}Request created successfully.</>");
        $this->newLine();

        // Create Model and migration
        Artisan::call('make:model', ['name'          => $baseFilesPath .'\\' .$controllerNameWithoutSuffix . 'Model', '--migration'   => true]);

        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API model ${controllerNameWithoutSuffix}Model created successfully.</>");
        $this->newLine();

        // Custom make:service command
        Artisan::call('make:service ' . $baseFilesPath .'/' .$controllerNameWithoutSuffix . 'Service');
        $this->line("\t<fg=white;bg=green>INFO</>\t <fg=white>API service ${controllerNameWithoutSuffix}Service created successfully.</>");
        $this->newLine();

        $this->info("\t<fg=white;bg=green>INFO</>\t <fg=white>API controller ${controllerNameWithoutSuffix}Controller created successfully.</>");
        $this->newLine();
    }
}
