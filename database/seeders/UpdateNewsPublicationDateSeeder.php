<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateNewsPublicationDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting to update news publication dates...');

        // Get all news that don't have a publication_date set
        $newsWithoutPublicationDate = News::whereNull('publication_date')->get();

        $count = 0;

        foreach ($newsWithoutPublicationDate as $news) {
            // Set publication_date to the created_at date
            $news->update([
                'publication_date' => $news->created_at->toDateString()
            ]);
            $count++;
        }

        $this->command->info("Updated {$count} news items with publication dates based on created_at.");

        // Alternative approach using raw SQL for better performance with large datasets
        // DB::statement('UPDATE news SET publication_date = DATE(created_at) WHERE publication_date IS NULL');
    }
}
