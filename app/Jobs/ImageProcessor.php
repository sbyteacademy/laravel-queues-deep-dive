<?php

namespace App\Jobs;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class ImageProcessor implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $photoName;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $photoName) {
        $this->email = $email;
        $this->photoName = $photoName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $attachmentsPath = storage_path('app/output/');
        $fileOutputPath = $attachmentsPath . pathinfo($this->photoName, PATHINFO_FILENAME);

        $jobs = [];
        $sizes = [500, 600, 700, 800, 900];

        foreach ($sizes as $size) {
            $jobs[] = new ImageResize($this->photoName, $size);
        }

        $email = $this->email;

        Bus::batch([
            ...$jobs,
        ])->then(function (Batch $batch) use ($sizes, $fileOutputPath, $email) {
            SendUserEmail::dispatch($email, $fileOutputPath, $sizes);
        })->catch(function (Batch $batch, \Throwable $e) {
            dd($e->getMessage());
        })->dispatch();

    }
}
