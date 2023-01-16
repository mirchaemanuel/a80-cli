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

        // @todo use VERSION.yml to retrieve version and release notes

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
                        <tr>
                            <td>0.1.3</td>
                            <td>2023-01-14</td>
                            <td>
                                <ul>
                                    <li>tools:image:thumb</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>0.1.4</td>
                            <td>2023-01-14</td>
                            <td>
                                <ul>
                                    <li>tools:image:list</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>0.1.5</td>
                            <td>2023-01-14</td>
                            <td>
                                <ul>
                                    <li>tools:image:list improved with thumbnail generation and report</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>0.1.6</td>
                            <td>2023-01-14</td>
                            <td>
                                <ul>
                                    <li>OpenAIService</li>
                                    <li>ai:title-abstract: generate title and abstract of text file</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        HTML
        );
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
