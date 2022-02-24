<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrawlerJOB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawlerjob:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler to get title of sites and saving them on database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      \App\Models\UrlShorteners::crawler();
      \Log::info('Crawler to get title of sites and saving them on database');
      $this->info('Crawler to get title of sites and saving them on database');
    }
}
