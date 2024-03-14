<?php

namespace App\Livewire;

use App\Jobs\WordProcessor;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Symfony\Contracts\Service\Attribute\Required;

class UploadWordComponent extends Component {
    use WithFileUploads;

    /**
     * @var TemporaryUploadedFile $document
     */
    #[Required]
    #[Validate('file|max:1024')]
    public $document;

    #[Required]
    #[Validate('email')]
    public $email;

    public function save() {
        $this->validate();

        $fileName = $this->document->getClientOriginalName();
        $this->document->storeAs(path: 'documents', name: $fileName);

        WordProcessor::dispatch($this->email, $fileName);

        $this->dispatch('word-file-processed');

    }

    public function render() {
        return view('livewire.upload-word-component');
    }
}
