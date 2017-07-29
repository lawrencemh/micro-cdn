<?php

namespace Tests\Functional\Api\Containers;

use Tests\Functional\Api\ApiTestCase;

class ContainerCrudTest extends ApiTestCase
{
    /**
     * Test an authorised user can create containers.
     *
     * @return bool
     */
    public function testAnAuthorisedUserCanCreateAContainer()
    {
        $user = factory('App\Models\User')->create();

        $this->call('POST', '/containers', [
            'api_token' => $user->api_token,
            'name' => 'testContainer',
        ]);

        $this->seeJsonStructure($this->apiResourceCreatedJsonStructure);

        $this->seeInDatabase('containers', [
            'user_id'   => $user->id,
            'name'      => 'testContainer',
        ]);

    }
}