<?php

/**
 * Created by Patricio Andrade
 * @github tricioandrade
 * @link https://github.com/tricioandrade
 *
 * LinkedIn
 * @link https://linkedin.com/tricioandrade
 */

namespace Enlighten\Builder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTraitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create trait command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $traitName   = $this->argument('name');
        $traitPath   = app_path().'\\Traits';
        $namespace  = 'App\\Traits';
        $traitStub   = File::get(resource_path('stubs/create.trait.stub'));

        if (str_contains($traitName, '/')) {
            $array      = explode('/', $traitName);
            $traitName   = end($array);

            if (!preg_match('/Trait$/', $traitName)){
                $traitName = end($array).'Trait';
            }

            array_pop($array);
            $traitPath = app_path().'\\Traits\\'.implode('/',$array);
            $namespace   = 'App\\Traits\\'. implode('\\', $array);

            if (!File::exists($traitPath)) {
                File::makeDirectory($traitPath, 0755, true);
            }
        }

        $traitStub = str_replace([ 'DummyTrait','DummyNamespace'], [$traitName, $namespace], $traitStub);
        $filePath = $traitPath. '\\' . $traitName . '.php';

        File::put($filePath, $traitStub);
        $this->info("Trait ${traitName} created successfully.");
    }
}
