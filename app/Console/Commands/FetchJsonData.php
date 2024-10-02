<?php

namespace App\Console\Commands;

use App\Models\Submission;
use Illuminate\Console\Command;

class FetchJsonData extends Command
{
    protected $signature = 'fetch:json-data';
    protected $description = 'Fetch JSON data and store it in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $jsonContent = file_get_contents(storage_path('app/submission.json'));
        if ($jsonContent === false) {
            $this->error('Failed to fetch JSON data.');
            return 1;
        }

        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Failed to decode JSON data.');
            return 1;
        }

        foreach ($data as $item) {
            Submission::updateOrCreate(
                ['submission_id' => $item['id']],
                ['name' => $item['name'], 'payloads' => $item['payloads']]
            );
        }

        $this->info('JSON data fetched and stored successfully.');
    }
}
