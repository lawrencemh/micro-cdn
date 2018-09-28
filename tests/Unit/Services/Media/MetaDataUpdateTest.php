<?php

namespace tests\Unit\Services\Media;

use App\Services\MediaService;
use Tests\Functional\Api\ApiTestCase;

class MetaDataUpdateTest extends ApiTestCase
{
    public function test_media_service_forbids_adding_forbidden_meta_data()
    {
        $mediaService = new MediaService();

        $media = factory('App\Models\Media')->make([
            'file_mime' => 'jpg',
        ]);

        $mediaService->update($media)->addOrUpdateUserMetaData([
            'file_mime' => 'this_should_not_update!',
        ]);

        $media = $media->refresh();
        $this->assertNotEquals($media->meta_data['file_mime'], 'this_should_not_update!');
    }
}
