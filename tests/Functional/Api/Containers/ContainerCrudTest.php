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
            'name'      => 'testContainer',
        ]);

        $this->seeJsonStructure($this->apiResourceCreatedJsonStructure);

        $this->assertResponseStatus(201);

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

        $this->assertResponseStatus(200);

        $this->seeJsonStructure(['data' => [['id'], ['id']]]);
    }

    /**
     * Test an authorised user can delete one of their containers.
     */
    public function testAnAuthorisedUserCanDeleteAContainer()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());
        $container = $user->containers()->first();

        $this->call('delete', "/containers/{$container->id}", [
            'api_token' => $user->api_token,
        ]);

        $this->seeJsonContains(['status' => 'deleted']);

        $this->assertResponseStatus(202);

        $this->notSeeInDatabase('containers', [
            'user_id'   => $user->id,
            'name'      => $container->name,
        ]);
    }

    /**
     * Test an authorised user can update one of their existing containers.
     */
    public function testAnAuthorisedUserCanUpdateAContainer()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());
        $container = $user->containers()->first();

        $this->call('patch', "/containers/{$container->id}", [
            'api_token' => $user->api_token,
            'name'      => 'NewTestName',
        ]);

        $this->seeJsonContains(['name' => 'NewTestName']);

        $this->assertResponseStatus(200);

        $this->notSeeInDatabase('containers', [
            'user_id'   => $user->id,
            'name'      => $container->name,
        ]);

        $this->seeInDatabase('containers', [
            'user_id'   => $user->id,
            'name'      => 'NewTestName',
        ]);
    }
}
