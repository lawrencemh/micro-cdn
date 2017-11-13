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

        $this->assertResponseStatus(201);

        $this->seeJsonStructure([
            'data' => [
                'id', 'attributes'
            ]]
        );

        $mediaItem = $container->refresh()->media->first();
        $filePath  = public_path($mediaItem->path);

        // delete the test image if it was successfully created
        if ($this->fileExists($filePath)) {
            unlink($filePath);
        }
    }

    public function test_a_user_can_update_an_images_meta_data()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());

        $container = $user->containers->first();
        $media     = factory('App\Models\Media')->make();
        $container->media()->save($media);

        $this->call('PATCH', "/containers/{$container->id}/media/{$media->id}", [
            'api_token' => $user->api_token,
        ], [], [
            'meta_data' => [
                'new_key' => true,
            ],
        ], ['Accept' => 'application/json']);

        $container = $container->refresh();
dd($this->response->getContent());
        $this->seeJsonContains(['meta_data' => ['new_key' => true]]);
    }
}

//[
//    'name' => 'test.jpg',
//    'meta_data' => [
//        'file_mime' => 'image/jpeg',
//        'has_been_processed' => true,
//        'can_be_processed' => true,
//    ],
//]
