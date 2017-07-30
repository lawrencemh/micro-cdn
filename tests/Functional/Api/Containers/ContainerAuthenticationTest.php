<?php

namespace Tests\Functional\Api\Containers;

use Tests\Functional\Api\ApiTestCase;

class ContainerAuthenticationTest extends ApiTestCase
{
    /**
     * Test an unauthorised user cannot create containers.
     */
    public function testAnNonAuthorisedUserCannotCreateAContainer()
    {
        $this->call('POST', '/containers', [
            'name' => 'testContainer',
        ]);

        $this->seeJsonContains($this->apiUnauthorisedRequestJsonResponse);
        $this->assertResponseStatus(401);
    }
}