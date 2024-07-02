<?php

namespace App\Commands\Tools;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class GenerateQRcode extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:tools:qrcode';

public function handle()
    {
        $text = $this->argument('text');
        $format = $this->argument('format');
        $size = $this->argument('size');

        $this->info("Generazione del QR Code in formato $format...");

        $qrCode = QrCode::format($format)->size($size)->generate($text);
        $outputPath = "qrcode.$format";
        \Storage::disk('local')->put($outputPath, $qrCode);

        $this->info("QR Code generato: storage/app/$outputPath");
    }

}
