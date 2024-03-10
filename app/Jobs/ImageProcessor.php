<?php

namespace App\Jobs;

use App\Mail\SendUserPhotos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        $sendUserPhoto = new SendUserPhotos();

        $photoPath = storage_path('app/photos/');
        $attachmentsPath = storage_path('app/output/');

        $filePath = $photoPath . $this->photoName;

        $manager = new ImageManager(
            new Driver()
        );

        $image = $manager->read($filePath);

        $image->scale(height: 100);
        $encoded = $image->toJpg();
        $fileOutputPath = $attachmentsPath . pathinfo($this->photoName, PATHINFO_FILENAME);

        $encoded->save($fileOutputPath . '-100.jpg');

        $sendUserPhoto->attach($fileOutputPath . '-100.jpg');

        $image->scale(height: 300);
        $encoded = $image->toJpg();
        $encoded->save($fileOutputPath . '-300.jpg');
        $sendUserPhoto->attach($fileOutputPath . '-300.jpg');

        \Mail::to($this->email)
            ->send($sendUserPhoto);

    }
}
