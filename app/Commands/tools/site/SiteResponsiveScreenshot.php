<?php

namespace App\Commands\tools\site;

use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use Laravel\Dusk\Browser;

class SiteResponsiveScreenshot extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:site:responsive-screenshot {url?}
                                                             {--cookiebot-accept : click to accept cookies on cookie bot banner}
                                                             {--p|prefix= : prefix for the output filename, timestamp if omitted}
                                                             ';
    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Take responsive screenshots of a website';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {

        // get options
        $cookieBotClick = $this->option('cookiebot-accept');
        $prefix = $this->option('prefix') ?? date('Ymd_His');

        //check url or prompt the user
        $url = $this->argument('url');
        if (!$url) {
            $url = $this->ask('What is the URL of the site to screenshot?');
        }

        if (!Str::startsWith($url, ['http://', 'https://'])) {
            $url = 'https://' . $url;
        }

        $this->browse(function (/** @var Browser */  $browser) use ($url, $cookieBotClick, $prefix) {

            try {

                /** @var Browser $browser */
                $browser = $browser->visit($url);
                if ($cookieBotClick) {
                    $this->info('Cookie bot click enabled');
                    if ($browser->element('#CybotCookiebotDialogBodyLevelButtonLevelOptinAllowAll')->isDisplayed()) {
                        $this->info('Cookie bot banner found');
                        $browser->click('#CybotCookiebotDialogBodyLevelButtonLevelOptinAllowAll')
                            ->pause(500);
                    }
                }
                //take screenshot
                $browser
                    ->responsiveScreenshots(Str::slug($prefix . ' ' . $url));
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        });
    }

    /**
     * Define the command's schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
