<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SendSimpleRequestJob;
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
        dispatch(new SendSimpleRequestJob('https://atomic.incfile.com/fakepost'));

        return 0;
    }
}
