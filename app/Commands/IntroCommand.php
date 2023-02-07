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
        try {
            $versionJson = json_decode(Storage::disk('internal')->get('resources/docs/VERSION.json'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->warn('Error parsing VERSION.json');
        }

        render(view('intro', [
            'releases' => $versionJson['versions'],
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
