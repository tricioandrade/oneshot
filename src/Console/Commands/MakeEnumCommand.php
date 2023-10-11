<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use OneShot\Builder\Enum\Templates\StubsFilesNameEnum;
use OneShot\Builder\Traits\EssentialsTrait;

class MakeEnumCommand extends Command
{
    use EssentialsTrait;

    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'make:enum {name}';

    /**
     * The Console command description.
     *
     * @var string
     */
    protected $description = 'Create Enum objects';

    /**
     * Execute the Console command.
     */
    public function handle(): void
    {
        $enumName   = $this->argument('name');
        $enumPath   = app_path().'\\Enums';
        $namespace  = 'App\\Enums';
        $enumStub   = File::get(base_path('stubs/' . StubsFilesNameEnum::ENUM->value));

        if (str_contains($enumName, '/')) {
            $array      = explode('/', $enumName);
            $enumName   = $this->addFileNameSuffix(end($array), 'Enum');

            array_pop($array);
            $enumPath = app_path().'\\Enums\\'.implode('/',$array);
            $namespace   = 'App\\Enums\\'. implode('\\', $array);
        }

        $this->createDir($enumPath);

        $enumStub = $this->replaceContent([ 'DummyClass','DummyNamespace'], [$enumName, $namespace], $enumStub);

        $this->storeContent($enumPath. '\\' . $enumName . '.php', $enumStub);
        $this->info("Enum " . $enumName . " created successfully.");
    }
}
