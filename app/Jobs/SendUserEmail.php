<?php

namespace App\Jobs;

use App\Mail\SendUserPhotos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendUserEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $fileOutputPath;
    private $size;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $fileOutputPath, $size) {
        $this->email = $email;
        $this->fileOutputPath = $fileOutputPath;
        $this->size = $size;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $sendUserPhoto = new SendUserPhotos();

        foreach ($this->size as $size) {
            $sendUserPhoto->attach($this->fileOutputPath . '-' . $size . '.jpg');
        }

        \Mail::to($this->email)
            ->send($sendUserPhoto);
    }
}
