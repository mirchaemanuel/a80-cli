<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
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
        $version = config('app.version');
        render(<<<HTML
        <div class="ml-2 mt-1">
            <div class="font-bold underline">
                A80-cli version {$version}
            </div>
            <div class="mb-1">
                Mircha Emanuel D'Angelo | https://a80.it
            </div>
            <div class="px-2">
                <div class="mb-1">Releases:</div>
                <table>
                    <thead>
                        <tr>
                            <th>Release</th>
                            <th>Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>0.1</td>
                            <td>2023-01-14</td>
                            <td>
                                <ul>
                                    <li>Initial release</li>
                                    <li>OpenAI client library</li>
                                    <li>ai:query command</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        HTML);
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
