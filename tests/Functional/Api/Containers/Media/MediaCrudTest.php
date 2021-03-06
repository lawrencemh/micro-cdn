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
                'id', 'attributes' => [
                    'small_path',
                    'medium_path',
                    'large_path',
                    'original_path',
                ],
            ], ]
        );

        $mediaItem = $container->refresh()->media->first();
        $filePath  = public_path($mediaItem->path);

        $this->assertFileExists($filePath);

        // Check compressed copies were created for the media item;
        $this->assertFileExists($mediaItem->small ? $mediaItem->small->getFullLocalPath() : null);
        $this->assertFileExists($mediaItem->medium ? $mediaItem->medium->getFullLocalPath() : null);
        $this->assertFileExists($mediaItem->large ? $mediaItem->large->getFullLocalPath() : null);

        // delete the test image if it was successfully created
        if ($this->fileExists($mediaItem->getFullLocalPath())) {
            unlink($mediaItem->getFullLocalPath());

            foreach ($mediaItem->compressedCopies as $copy) {
                unlink($copy->getFullLocalPath());
            }
        }
    }

    public function test_a_user_can_update_an_images_meta_data()
    {
        $user = factory('App\Models\User')->create();
        $user->containers()->save(factory('App\Models\Container')->make());

        $container = $user->containers->first();
        $media     = factory('App\Models\Media')->make();
        $container->media()->save($media);

        $this->call('patch', "containers/{$container->id}/media/{$media->id}", [
            'api_token' => $user->api_token,
            'meta_data' => [
                'new_key' => true,
            ],
        ], [], [], ['Accept' => 'application/json']);

        $this->seeJsonContains(['new_key' => true]);

        $media = $media->refresh();
        $this->assertArrayHasKey('new_key', $media->meta_data);
        $this->assertEquals(true, $media->meta_data['new_key']);
    }

    public function test_a_user_can_delete_a_media_item()
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

        $mediaItem = $container->refresh()->media->first();
        $filePath  = public_path($mediaItem->path);

        $this->assertFileExists($filePath);

        $this->call('delete', "containers/{$container->id}/media/{$mediaItem->id}", [
            'api_token' => $user->api_token,
        ], [], [], ['Accept' => 'application/json']);

        $this->seeJsonContains(['status' => 'deleted']);

        $this->assertResponseStatus(202);

        $this->assertFileNotExists($filePath);

        foreach ($mediaItem->compressedCopies as $copy) {
            $this->assertFileNotExists($copy->getFullLocalPath());
        }
    }
}
