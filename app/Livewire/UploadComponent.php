<?php

namespace App\Livewire;

use App\Jobs\ImageProcessor;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Symfony\Contracts\Service\Attribute\Required;

class UploadComponent extends Component {
    use WithFileUploads;

    #[Required]
    #[Validate('email')]
    public $email;
    /**
     * @var TemporaryUploadedFile $photo
     */
    #[Required]
    #[Validate('image|max:1024')]
    public $photo;

    public function save() {
        $this->validate();
        $fileName = $this->photo->getClientOriginalName();
        $this->photo->storeAs(path: 'photos', name: $fileName);

        ImageProcessor::dispatch($this->email, $fileName);

        $this->dispatch('image-processed');

    }

    public function render() {
        return view('livewire.upload-component');
    }
}
