<?php

namespace App\Jobs\Media;

use App\Jobs\Job;
use App\Models\Media;
use App\Services\MediaService;

class DeleteMediaJob extends Job
{
    /**
     * The media instance.
     *
     * @var \App\Models\Media
     */
    protected $media;

    /**
     * The media service instance.
     *
     * @var \App\Services\MediaService
     */
    protected $mediaService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Media $media, MediaService $mediaService)
    {
        $this->media = $media;
        $this->mediaService = $mediaService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->mediaService->delete($this->media);
    }
}
