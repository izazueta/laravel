<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class SendSimpleRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The URL to send a request.
     *
     * @var string
     */
    public $url;

    /**
     * The maximum number of jobs to process simultaneously.
     *
     * @var int
     */
    protected $max_jobs = 100;

    /**
     * The time in seconds to wait before processing more jobs.
     *
     * @var int
     */
    protected $wait = 10;

    /**
     * Create a new job instance.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @throws LimiterTimeoutException
     * @return void
     */
    public function handle(): void
    {
        Redis::throttle('fakepost')
            ->allow($this->max_jobs)
            ->every($this->wait)
            ->then(function () {
                info("Making a simple post request to {$this->url}");

                $response = Http::post($this->url);

                info("Status: {$response->status()}");

                // Release the job to retry later if the request failed until max tries reached
                if ($response->failed()) {
                    $this->release($this->wait);
                }
            }, function () {
                // Release the job to retry later if we can't obtain a lock
                $this->release($this->wait);
            });
    }
}
