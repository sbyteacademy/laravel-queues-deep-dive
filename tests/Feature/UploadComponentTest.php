<?php

use App\Jobs\ImageProcessor;
use App\Livewire\UploadComponent;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

test('testing upload component background job', function () {
    Queue::fake();

    Storage::fake('images');
    $file = UploadedFile::fake()->image('minicourse.jpg');

    Livewire::test(UploadComponent::class)
        ->set('email', 'admin@sbyte.academy')
        ->set('photo', $file)
        ->call('save');

    Queue::assertPushed(ImageProcessor::class);

});

test('testing upload component background batch', function () {
    Bus::fake();

    Storage::fake('images');
    $file = UploadedFile::fake()->image('minicourse.jpg');

    Livewire::test(UploadComponent::class)
        ->set('email', 'admin@sbyte.academy')
        ->set('photo', $file)
        ->call('save');

    Bus::assertBatchCount(1);

});
