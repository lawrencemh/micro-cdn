<?php

namespace Tests\Functional\Api;

use Laravel\Lumen\Testing\DatabaseMigrations;
use TestCase;

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
            'attributes' => ['name'],
        ],
    ];

    /**
     * The expected json array response for requests that are unauthorised.
     *
     * @var array
     */
    protected $apiUnauthorisedRequestJsonResponse = [
        'errors' => [
            'Unauthorized',
        ],
    ];

    /**
     * The expected json array response for requests that return an empty object set.
     *
     * @var array
     */
    protected $apiEmptyObjectJsonResponse = [
        'data' => null,
    ];

    /**
     * The expected json array response for resource not found 404 requests.
     *
     * @var array
     */
    protected $apiResourceNotFoundJsonResponse = [
        'errors' => [
            'Resource not found',
        ],
    ];
}
