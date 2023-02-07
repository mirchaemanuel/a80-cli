<?php

namespace App\Commands\tools;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Json2YamlCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:json2yaml {file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Convert JSON file to YAML. The YAML file will be printed to the console';

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
            $file = $this->ask('Please enter JSON file to convert:');
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
        try {
            $parsedJson = json_decode(File::get($file), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->error('Error decoding json: ' . $e->getMessage());
            return;
        }

        $parsedYaml = yaml_emit($parsedJson);
        $this->info($parsedYaml);
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
