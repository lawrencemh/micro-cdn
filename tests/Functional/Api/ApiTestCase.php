<?php

namespace Tests\Functional\Api;

use TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ApiTestCase extends TestCase
{
    use DatabaseMigrations;

    /**
     * The expected json array response for successful entity creation.
     *
     * @var array
     */
    protected $apiResourceCreatedJsonStructure = [
        'data' => [
            'id',
            'type',
            'status',
            'attributes' => ['name']
        ],
    ];

}