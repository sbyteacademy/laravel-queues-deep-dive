<?php

namespace App\Jobs\Workflows;

use App\Jobs\CreateAudioTranscription;
use App\Jobs\NotifySubscribers;
use App\Jobs\OptimizePodcast;
use App\Jobs\ProcessPodcast;
use App\Jobs\ReleaseOnApplePodcasts;
use App\Jobs\ReleaseOnTransistorFM;
use App\Jobs\SendTweetAboutNewPodcast;
use App\Jobs\TranslateAudioTranscription;
use Sassnowski\Venture\AbstractWorkflow;

class PublishNewPodcastWorkflow extends AbstractWorkflow {

    public function __construct() {
    }

    public function definition(): \Sassnowski\Venture\WorkflowDefinition {

        return $this->define('Publish new podcast')
            ->addJob(new ProcessPodcast())
            ->addJob(new OptimizePodcast())
            ->addJob(new ReleaseOnTransistorFM(), [
                ProcessPodcast::class,
                OptimizePodcast::class
            ])
            ->addJob(new ReleaseOnApplePodcasts(), [
                ProcessPodcast::class,
                OptimizePodcast::class
            ])
            ->addJob(new CreateAudioTranscription(), [
                ProcessPodcast::class,
            ])
            ->addJob(new TranslateAudioTranscription(), [
                CreateAudioTranscription::class,
            ])
            ->addJob(new NotifySubscribers(), [
                ReleaseOnTransistorFM::class,
                ReleaseOnApplePodcasts::class,
            ])
            ->addJob(new SendTweetAboutNewPodcast(), [
                TranslateAudioTranscription::class,
                ReleaseOnTransistorFM::class,
                ReleaseOnApplePodcasts::class,
            ]);
    }
}
