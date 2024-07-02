<?php

namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use League\HTMLToMarkdown\HtmlConverter;

class JsonArticleFetcher extends Command
{

    protected $signature = 'process:articles';
    protected $description = 'Converts WordPress articles from JSON to Markdown and downloads images.';

    public function handle()
    {
        $directory = 'oflarinascentecom_blog_2024_04_17/'; // Modifica con il percorso della tua directory
        $files = Storage::allFiles($directory);
        foreach ($files as $file) {
            $jsonData = json_decode(Storage::get($file), true);

            if (!$jsonData) {
                $this->error("Error reading {$file}");
                continue;
            }

            // Crea la directory per l'articolo
            $articleDir = 'oflarinascentecom_blog_2024_04_17/' . Str::slug($jsonData['title']['rendered']) . '_' . $jsonData['id'];
            Storage::makeDirectory($articleDir);

            // Converti l'HTML in Markdown
            $converter = new HtmlConverter(['strip_tags' => true]);
            $markdownContent = $converter->convert($jsonData['content']['rendered']);
            $markdownTitle = '# ' . $jsonData['title']['rendered'];
            $markdownExcerpt = '> ' . strip_tags($jsonData['excerpt']['rendered']);

            // Salva il file Markdown
            $markdownData = "{$markdownTitle}\n\n{$markdownExcerpt}\n\n{$markdownContent}\n";
            Storage::put("{$articleDir}/article.md", $markdownData);

            // Gestisci l'immagine in evidenza
            $this->downloadFeaturedImage($jsonData['featured_media'], $articleDir);
        }

        $this->info('Articles have been processed.');
    }

    protected function downloadFeaturedImage($mediaId, $directory)
    {
        if ($mediaId) {
            $client = new Client();
            $url = 'https://oflarinascente.com/wp-json/wp/v2/media/' . $mediaId;
            try {
                $response = $client->request('GET', $url);
                $mediaData = json_decode($response->getBody()->getContents(), true);
                $imageUrl = $mediaData['source_url'];
                $imageContent = file_get_contents($imageUrl);
                $filename = basename($imageUrl);
                Storage::put("{$directory}/{$filename}", $imageContent);
            } catch (\Exception $e) {
                $this->error("Failed to download image: {$e->getMessage()}");
            }
        }
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
