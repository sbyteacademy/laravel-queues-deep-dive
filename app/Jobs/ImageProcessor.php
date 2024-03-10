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
        $filePath = storage_path('app/photos/') . $this->photoName;

        $sendUserPhoto = new SendUserPhotos();
        $sendUserPhoto->attach($filePath);

        \Mail::to($this->email)
            ->send($sendUserPhoto);

    }
}
