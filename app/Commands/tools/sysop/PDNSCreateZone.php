<?php

namespace App\Commands\Tools\sysop;

use Exonet\Powerdns\Powerdns;
use Exonet\Powerdns\RecordType;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class PDNSCreateZone extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:pdns:create-zone {--S|server=} {--k|key=} {--p|port=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new zone in PowerDNS server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // prerequisities: pdns server, port and api key
        $server = $this->option('server');
        if (!$server || filter_var($server, FILTER_VALIDATE_URL) === false) {
            $server = text(
                label: 'Enter the PowerDNS server host/IP',
                required: true,
                validate: fn(string $value) => match (true) {
                    filter_var($value, FILTER_VALIDATE_URL) === false => 'The value must be a valid URL',
                    default => null
                },
                hint: 'https://192.168.1.1'
            );
        }

        $port = $this->option('port');
        if (!is_numeric($port) || $port < 1 || $port > 255) {
            // confirm default port
            if (confirm('Use default port 8081?')) {
                $port = 8081;
            } else {
                $port = text(
                    label: 'Enter the PowerDNS server port',
                    required: true,
                    validate: fn(string $value) => match (true) {
                        filter_var($value, FILTER_VALIDATE_INT) === false || $value < 1 || $value > 255 => 'The value must be a valid port number.',
                        default => null
                    },
                    hint: '8081'
                );
            }
        }

        $key = $this->option('key');
        if (empty($key)) {
            $key = text(
                label: 'Enter the PowerDNS API key',
                required: true,
            );
        }

        // summary of the options
        $this->info('PowerDNS server: ' . $server . ':' . $port);

        if ($this->option('verbose')) {
            $this->info('PowerDNS API key: ' . $key);
        }

        // create PowerDNS instance
        $powerdns = new Powerdns(
            host: $server,
            apiKey: $key,
            port: $port
        );

        // todo to be continued
        $this->info('Not implemented yet');

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
