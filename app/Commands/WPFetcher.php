<?php

namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class WPFetcher extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:wp-fetcher';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Fetches articles from a specified WordPress category and stores them locally';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Chiede all'utente l'indirizzo del sito WordPress
        $url = $this->ask('Enter the WordPress site URL');

        $client = new Client();
        try {
            $response = $client->request('GET', $url . '/wp-json/wp/v2/categories');
            $categories = json_decode($response->getBody()->getContents());

            // Mostra le categorie all'utente
            $categoriesNames = [];
            foreach ($categories as $category) {
                $categoriesNames[$category->id] = $category->name;
            }
            $categoryName = $this->choice('Select a category', $categoriesNames);
            $categoryId = array_search($categoryName, $categoriesNames);

            $response = $client->request('GET', $url . "/wp-json/wp/v2/posts?categories=$categoryId&per_page=100");
            $totalArticles = $response->getHeader('X-WP-Total')[0];
            $totalPages = $response->getHeader('X-WP-TotalPages')[0];
            $this->info("Total articles to fetch: $totalArticles across $totalPages pages.");

            $date = now()->format('Y-m-d');
            $directory = Str::slug($url . '_' . $categoryName . '_' . $date, '_');
            Storage::makeDirectory($directory);

            $progressBar = $this->output->createProgressBar($totalArticles);
            $progressBar->start();

            for ($page = 1; $page <= $totalPages; $page++) {
                $response = $client->request('GET', $url . "/wp-json/wp/v2/posts?categories=$categoryId&per_page=100&page=$page");
                $posts = json_decode($response->getBody()->getContents());
                foreach ($posts as $post) {
                    Storage::put("{$directory}/{$post->id}.json", json_encode($post));
                    $progressBar->advance();
                }
            }

            $progressBar->finish();
            $this->info("\nData has been saved successfully!");
        } catch (\Exception $e) {
            $this->error('Failed to fetch data: ' . $e->getMessage());
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
