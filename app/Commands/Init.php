<?php

namespace App\Commands;

use App\Configuration\DefaultConfig;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Yaml\Yaml;
use Throwable;

class Init extends Command
{
    protected $signature = 'init {filename=tclint.yaml} {--force}';

    protected $description = 'Initialize the config file';

    public function handle(): int
    {
        $filename = $this->argument('filename');

        if (File::exists($filename) && ! $this->option('force')) {
            $this->error("The file $filename already exists.");

            return self::FAILURE;
        }

        try {
            File::put($filename, Yaml::dump(DefaultConfig::DEFAULT_CONFIG, 4));
        } catch (Throwable $e) {
            $this->error('Config file generate failed. error: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info("Config file generated at $filename successfully.");

        return self::SUCCESS;
    }
}
