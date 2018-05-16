<?php

namespace App\Jobs\Container;

use App\Jobs\Job;
use App\Models\Container;
use App\Services\ContainerService;

class DeleteContainerJob extends Job
{
    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * The container service instance.
     *
     * @var \App\Services\ContainerService
     */
    protected $containerService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $container, ContainerService $containerService)
    {
        $this->container = $container;
        $this->containerService = $containerService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->containerService->deleteContainer($this->container);
    }
}
