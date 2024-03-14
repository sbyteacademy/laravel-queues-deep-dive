<?php

use App\Jobs\SendTweetAboutNewPodcast;
use App\Jobs\Workflows\PublishNewPodcastWorkflow;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('jobs', function () {
    Bus::chain([
        Bus::batch([
            new \App\Jobs\OptimizePodcast(),
            new \App\Jobs\ProcessPodcast(),
        ]),
        Bus::batch([
            new \App\Jobs\ReleaseOnApplePodcasts(),
            new \App\Jobs\ReleaseOnTransistorFM(),
            new \App\Jobs\CreateAudioTranscription()
        ]),
        Bus::batch([
            new \App\Jobs\NotifySubscribers(),
            new \App\Jobs\TranslateAudioTranscription()
        ]),
        new SendTweetAboutNewPodcast()
    ])->dispatch();
});

Route::get('workflows', function () {
    $workflow = PublishNewPodcastWorkflow::start();

    dd($workflow->remainingJobs());
});

Route::view('/', 'welcome');

Route::view('/convert', 'convert');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
