<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;

class PublishConfigPackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oneshot:publish';

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
        $this->info('Publishing <fg=green>oneshot</> package...');

        $this->publishConfig();

        $this->info('Package published successfully!');
    }

    protected function publishConfig()
    {
        $sourcePath = __DIR__.'/../../../config/oneshot.php';
        $destinationPath = config_path('oneshot.php');

        if (!file_exists($destinationPath)) {
            copy($sourcePath, $destinationPath);
        }
    }
}
