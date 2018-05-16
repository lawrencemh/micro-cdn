<?php

namespace App\Console\Commands\Media;

use App\Services\MediaService;
use Illuminate\Console\Command;

class CreateCompressedCopies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:compressed-copies';

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected $mediaService;

    /**
     * CreateUser constructor.
     *
     * @param \App\Models\User $user
     * @param \Faker\Factory   $faker
     */
    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
        Parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $mediaItems = $this->mediaService->all();

        $this->info("You are about to create compressed copies for {$mediaItems->count()} images");
        if ($this->confirm('Do you wish to continue?', false) === false) {
            return 3;
        }

        foreach ($mediaItems as $item) {
            if ($item->canBeCompressed()) {
                // Create compressed versions of the image
                $job = (new \App\Jobs\Media\CreateOptimisedCopiesMediaJob($item));
                dispatch($job);
                $this->info("Scheduled media #{$item->id} to have compressed copies created");
            }
        }

        return 0;
    }
}
