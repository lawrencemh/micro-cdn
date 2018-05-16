<?php

namespace App\Jobs\Media;

use App\Jobs\Job;
use App\Models\Media;
use App\Services\ImageOptimiserService;

class CreateOptimisedCopiesMediaJob extends Job
{
    /**
     * The media instance.
     *
     * @var \App\Models\Media
     */
    protected $media;

    /**
     * The image optimisation service instance.
     *
     * @var \App\Services\ImageOptimiserService
     */
    protected $imageOptimiserService;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Media $media
     *
     * @return void
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new ImageOptimiserService($this->media))->createCompressedCopies();
    }
}
