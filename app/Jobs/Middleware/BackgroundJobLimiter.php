<?php

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class BackgroundJobLimiter {

    public function handle(object $job, Closure $next) {
        Redis::throttle('background-jobs')
            ->allow(1)
            ->every(30)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release(10);
            });

    }
}
