<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class SendSimpleRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incfile:send-simple-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a simple POST request';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $url = 'https://atomic.incfile.com/fakepost';
        $max_retries = 5;
        $wait = 1000;

        $this->info("Making a simple post request to $url");

        try {
            $response = Http::retry($max_retries, $wait)->post($url);
            $this->info("Status: {$response->status()}");
        } catch(RequestException $exception) {
            $this->error("The request failed, max attempts: $max_retries reached.");
        }

        return 0;
    }
}
