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

    /**
     * Test one user cannot view another's containers.
     */
    public function testAUserCannotSeeAnotherUsersContainers()
    {
        $firstUser = factory('App\Models\User')->create();
        $firstUser->containers()->save(factory('App\Models\Container')->make());

        $secondUser = factory('App\Models\User')->create();

        $this->call('get', '/containers', [
            'api_token' => $secondUser->api_token,
        ]);

        $this->seeJsonContains($this->apiEmptyObjectJsonResponse);
        $this->assertResponseStatus(200);
    }

    /**
     * Test one user cannot view another user's container.
     */
    public function testAUserCannotViewAnotherUsersContainer()
    {
        $firstUser = factory('App\Models\User')->create();
        $firstUser->containers()->save(factory('App\Models\Container')->make());
        $container = $firstUser->containers()->first();

        $secondUser = factory('App\Models\User')->create();

        $this->call('get', "/containers/{$container->id}", [
            'api_token' => $secondUser->api_token,
        ]);

        $this->seeJsonContains($this->apiResourceNotFoundJsonResponse);
        $this->assertResponseStatus(404);
    }

    /**
     * Test one user cannot delete another user's container.
     */
    public function testAUserCannotDeleteAnotherUsersContainer()
    {
        $firstUser = factory('App\Models\User')->create();
        $firstUser->containers()->save(factory('App\Models\Container')->make());
        $container = $firstUser->containers()->first();

        $secondUser = factory('App\Models\User')->create();

        $this->call('delete', "/containers/{$container->id}", [
            'api_token' => $secondUser->api_token,
        ]);

        $this->seeJsonContains($this->apiResourceNotFoundJsonResponse);
        $this->assertResponseStatus(404);

        $this->seeInDatabase('containers', [
            'user_id'   => $firstUser->id,
            'name'      => $container->name,
        ]);
    }

    /**
     * Test one user cannot update another user's container.
     */
    public function testAUserCannotUpdateAnotherUsersContainer()
    {
        $firstUser = factory('App\Models\User')->create();
        $firstUser->containers()->save(factory('App\Models\Container')->make());
        $container = $firstUser->containers()->first();

        $secondUser = factory('App\Models\User')->create();

        $this->call('patch', "/containers/{$container->id}", [
            'api_token' => $secondUser->api_token,
            'name'      => 'NewTestName',
        ]);

        $this->seeJsonContains($this->apiResourceNotFoundJsonResponse);
        $this->assertResponseStatus(404);

        $this->seeInDatabase('containers', [
            'user_id'   => $firstUser->id,
            'name'      => $container->name,
        ]);

        $this->NotSeeInDatabase('containers', [
            'user_id'   => $firstUser->id,
            'name'      => 'NewTestName',
        ]);
    }


}