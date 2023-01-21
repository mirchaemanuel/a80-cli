<?php

namespace App\Commands;

use App\Utils\CheckDotEnv;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class EnvCreateCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'env:create';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a .env file skeleton if not exists';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //check if .env exists, otherwise create it
        $this->task('creating .env file', function () {
            CheckDotEnv::create();
            return true;
        });
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
