<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use OneShot\Builder\Traits\EssentialsTrait;

class MakeTraitCommand extends Command
{
    use EssentialsTrait;
    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The Console command description.
     *
     * @var string
     */
    protected $description = 'Create trait command';

    /**
     * Execute the Console command.
     */
    public function handle()
    {
        $traitName   = $this->argument('name');
        $traitPath   = app_path().'\\Traits';
        $namespace  = 'App\\Traits';
        $traitStub   = File::get(base_path('stubs/create.trait.stub'));

        if (str_contains($traitName, '/')) {
            $array      = explode('/', $traitName);
            $traitName  = end($array);
            $traitName  = $this->addFileNameSuffix(end($array), 'Trait');

            array_pop($array);
            $traitPath = app_path().'\\Traits\\'.implode('/',$array);
            $namespace = 'App\\Traits\\'. implode('\\', $array);
        }

        $this->createDir($traitPath);

        $traitStub  = $this->replaceContent([ 'DummyTrait','DummyNamespace'], [$traitName, $namespace], $traitStub);
        $filePath   = $traitPath. '\\' . $traitName . '.php';

        $this->storeContent($filePath, $traitStub);
        $this->info("Trait ${traitName} created successfully.");
    }
}
