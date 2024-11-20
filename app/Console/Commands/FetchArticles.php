<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ArticleController;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = app(ArticleController::class);
        
        $controller->getLeMondeArticles();
        $controller->getLequipeArticles();
        // $controller->getLeParisienArticles();

        $this->info('Articles fetched successfully!');
    }
}
