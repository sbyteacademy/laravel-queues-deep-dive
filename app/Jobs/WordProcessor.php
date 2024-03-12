<?php

namespace App\Jobs;

use App\Mail\SendUserPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class WordProcessor implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $fileName) {
        $this->email = $email;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $documentPath = storage_path('app/documents/');

        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = base_path('/vendor/dompdf/dompdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

        $filePath = $documentPath . $this->fileName;
        $wordDocument = IOFactory::load($filePath);

        $writer = IOFactory::createWriter($wordDocument, 'PDF');

        $pdfPath = $documentPath . pathinfo($this->fileName, PATHINFO_FILENAME) . '.pdf';
        $writer->save($pdfPath);


        $sendUsersPdf = new SendUserPdf();
        $sendUsersPdf->attach($pdfPath);

        \Mail::to($this->email)->send($sendUsersPdf);
    }
}
