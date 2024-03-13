<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageResize implements ShouldQueue {
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $attachmentPath;
    private $fileName;
    private $filePath;
    private $size;

    /**
     * Create a new job instance.
     */
    public function __construct($fileName, $size) {
        $this->fileName = $fileName;
        $this->size = $size;

        $photoPath = storage_path('app/photos/');
        $attachmentsPath = storage_path('app/output/');

        $this->filePath = $photoPath . $this->fileName;
        $this->attachmentPath = $attachmentsPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {

        $manager = new ImageManager(
            new Driver()
        );

        $image = $manager->read($this->filePath);

        $image->scale(height: $this->size);
        $encoded = $image->toJpg();
        $fileOutputPath = $this->attachmentPath . pathinfo($this->fileName, PATHINFO_FILENAME);

        $encoded->save($fileOutputPath . '-' . $this->size . '.jpg');

    }

    public function middleware(): array {
        return [
            (new WithoutOverlapping('shared-key-user-1'))
                ->releaseAfter(10)
                ->shared()
        ];
    }
}
