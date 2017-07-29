<?php

namespace Tests\Functional\Api\Containers;

use Tests\Functional\Api\ApiTestCase;

class ContainerCrudTest extends ApiTestCase
{
    /**
     * Test an authorised user can create containers.
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

    /**
     * Test an authorised user can view all their containers.
     */
    public function testAnAuthorisedUserCanViewTheirContainers()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());
        $user->containers()->save(factory('App\Models\Container')->make());

        $this->call('get', '/containers', [
            'api_token' => $user->api_token,
        ]);
        $this->seeJsonStructure(['data' => [['id'],['id']]]);
    }
}