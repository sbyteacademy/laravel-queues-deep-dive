<?php

namespace App\Jobs;

use App\Mail\SendUserPhotos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImageProcessor implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private \Intervention\Image\Interfaces\ImageInterface $image;
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
        $attachmentsPath = storage_path('app/output/');
        $fileOutputPath = $attachmentsPath . pathinfo($this->photoName, PATHINFO_FILENAME);

        ImageResize::dispatch($this->photoName, 500);
        ImageResize::dispatch($this->photoName, 600);
        ImageResize::dispatch($this->photoName, 700);

        $sendUserPhoto
            ->attach($fileOutputPath . '-500.jpg')
            ->attach($fileOutputPath . '-600.jpg')
            ->attach($fileOutputPath . '-700.jpg');

        \Mail::to($this->email)
            ->send($sendUserPhoto);

    }
}
