<?php

namespace App\Commands;

use App\Utils\CheckDotEnv;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CheckCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'check';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check all system preconditions and requirements';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Checking system preconditions and requirements...');

        //check if .env file exists, otherwise create it
        $this->task('.env file', function () {
            return CheckDotEnv::exists();
        });

        //check yaml extension
        $this->task('yaml extension', function () {
            return function_exists('yaml_parse');
        });

        //check imagick extensionk
        if($this->task('imagick extension', function () {
            return extension_loaded('imagick');
        }));
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
