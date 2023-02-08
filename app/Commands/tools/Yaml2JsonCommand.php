<?php

namespace App\Commands\tools;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Yaml2JsonCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:yaml2json {file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Convert YAML file to JSON. The JSON file will be printed to the console';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * check Yaml extension
         */
        if (!extension_loaded('yaml')) {
            $this->error('Yaml extension not found');
            $this->info('https://www.php.net/manual/en/yaml.installation.php');
            return;
        }

        /**
         * check file argument, or ask if missing
         */
        if (!($file = $this->argument('file'))) {
            $file = $this->ask('Please enter file yaml to convert:');
        }

        /**
         * check file
         */
        if (!File::exists($file)) {
            $this->error('File not found');
            return;
        }

        /**
         * convert
         */
        $parsedYaml = yaml_parse(File::get($file));
        if ($parsedYaml === false) {
            $this->error('Error parsing yaml');
            return;
        }

        try {
            $json = json_encode($parsedYaml, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            $this->info($json);
        } catch (\JsonException $e) {
            $this->error('Error encoding json: ' . $e->getMessage());
            return;
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
