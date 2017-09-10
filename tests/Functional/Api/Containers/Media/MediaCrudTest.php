<?php

namespace Tests\Functional\Api\Containers\Media;

use Illuminate\Http\UploadedFile;
use Tests\Functional\Api\ApiTestCase;

class MediaCrudTest extends ApiTestCase
{
    public function test_a_user_can_upload_an_image_to_their_container()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());
        $container = $user->containers->first();
        $image     = UploadedFile::fake()->image('file.jpg', 600, 600);

        $this->call('POST', "/containers/{$container->id}/media", [
            'api_token' => $user->api_token,
        ], [], [
            'media_item' => $image,
        ], ['Accept' => 'application/json']);

        $this->assertTrue(true);
        dd($this->response->content());
    }
}
