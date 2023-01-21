<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use function Termwind\{render};

class IntroCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'intro';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'A little brief about the application and release notes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!function_exists('yaml_parse')) {
            $this->warning('YAML extension is not installed. Please install it to enable all functionalities');
            $versionYml = ['versions' => []];
        } else {
            //read file VERSION.yml if exists
            $versionYml = yaml_parse(Storage::disk('internal')->get('resources/docs/VERSION.yml'));
        }

        render(view('intro', [
            'releases' => $versionYml['versions'],
        ]));

    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
