<?php

namespace Tests\Functional\Api\Containers;

use Tests\Functional\Api\ApiTestCase;

class ContainerAuthenticationTest extends ApiTestCase
{
    /**
     * Test an unauthorised user cannot create containers.
     */
    public function testANonAuthorisedUserCannotCreateAContainer()
    {
        $this->call('POST', '/containers', [
            'name' => 'testContainer',
        ]);

        $this->seeJsonContains($this->apiUnauthorisedRequestJsonResponse);
        $this->assertResponseStatus(401);
    }

    /**
     * Test an unauthorised user cannot view containers.
     */
    public function testANonAuthorisedUserCannotViewContainers()
    {
        $this->call('get', '/containers');

        $this->seeJsonContains($this->apiUnauthorisedRequestJsonResponse);
        $this->assertResponseStatus(401);
    }

    /**
     * Test an unauthorised user cannot delete a container.
     */
    public function testANonAuthorisedUserCannotDeleteAContainer()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());
        $container = $user->containers()->first();

        $this->call('delete', "/containers/{$container->id}");

        $this->seeJsonContains($this->apiUnauthorisedRequestJsonResponse);
        $this->assertResponseStatus(401);

        $this->seeInDatabase('containers', [
            'user_id'   => $user->id,
            'name'      => $container->name,
        ]);
    }

    /**
     * Test an unauthorised user cannot update a container.
     */
    public function testANonAuthorisedUserCannotUpdateAContainer()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());
        $container = $user->containers()->first();

        $this->call('patch', "/containers/{$container->id}", [
            'name'      => 'NewTestName',
        ]);

        $this->seeJsonContains($this->apiUnauthorisedRequestJsonResponse);
        $this->assertResponseStatus(401);

        $this->seeInDatabase('containers', [
            'user_id'   => $user->id,
            'name'      => $container->name,
        ]);

        $this->NotSeeInDatabase('containers', [
            'user_id'   => $user->id,
            'name'      => 'NewTestName',
        ]);
    }
}