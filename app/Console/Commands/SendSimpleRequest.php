<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

        $this->info("Making a simple post request to $url");

        $response = Http::post($url);

        $this->info("Status: {$response->status()}");

        return 0;
    }
}
