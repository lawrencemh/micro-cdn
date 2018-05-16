<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\Media\DeleteMediaJob;
use App\Services\ContainerService;
use App\Services\MediaService;
use Illuminate\Http\Request;

class ContainerMediaController extends Controller
{
    protected $containerService;

    /**
     * The media service instance.
     *
     * @var \App\Services\MediaService
     */
    protected $mediaService;

    /**
     * ContainerMediaController constructor.
     *
     * @param \App\Services\ContainerService $containerService
     * @param \App\Services\MediaService     $mediaService
     *
     * @return void
     */
    public function __construct(ContainerService $containerService, MediaService $mediaService)
    {
        $this->middleware('auth');

        $this->containerService = $containerService;
        $this->mediaService = $mediaService;
    }

    /**
     * Return a JSON list of all the authorised user's media items for the given container.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $containerId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $containerId)
    {
        try {
            $container = $this->containerService->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItems = $this->mediaService->getAllMediaBelongingToContainer($container);

            return $this->responseService()->json()
                ->setReturnObject($mediaItems->toArray(), 'Media')
                ->render();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return $this->responseService()->json()
                ->resourceNotFound();
        }
    }

    /**
     * Return a JSON view for the given media item.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $containerId
     * @param int                      $mediaId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $containerId, $mediaId)
    {
        try {
            $container = $this->containerService->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItem = $this->mediaService->getMediaItemBelongingToContainer($container, $mediaId);

            return $this->responseService()->json()
                ->setReturnObject($mediaItem->toArray(), 'Media')
                ->render();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return $this->responseService()->json()
                ->resourceNotFound();
        }
    }

    /**
     * Create a new media item for the given container.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $containerId
     *
     * @return string
     */
    public function store(Request $request, $containerId)
    {
        $this->validate($request, [
            'media_item' => 'file|image',
        ]);

        if ($request->hasFile('media_item') && $request->file('media_item')->isValid()) {
            $container = $this->containerService->getContainerBelongingToUser($request->user(), $containerId);

            // call media creation service.
            $mediaItem = $this->mediaService->createMediaItem($container, $request->file('media_item'))->save();
        }

        return $this->responseService()->json()
            ->setReturnObject($mediaItem->toArray(), 'Media')
            ->setResponseCode(201)
            ->render();
    }

    /**
     * Update the given container's media item in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $containerId
     * @param int                      $mediaId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $containerId, $mediaId)
    {
        try {
            $container = $this->containerService->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItem = $this->mediaService->getMediaItemBelongingToContainer($container, $mediaId);

            $this->validate($request, [
                'meta_data' => 'array',
            ]);

            // Call media update service
            $this->mediaService->update($mediaItem)->addOrUpdateUserMetaData($request->get('meta_data'))
                ->saveAndRetrieve();

            return $this->responseService()->json()
                ->setReturnObject($mediaItem->toArray(), 'Media')
                ->setResponseCode(200)
                ->render();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return $this->responseService()->json()
                ->resourceNotFound();
        }
    }

    /**
     * Delete the given container's media item from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $containerId
     * @param int                      $mediaId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $containerId, $mediaId)
    {
        try {
            $container = $this->containerService->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItem = $this->mediaService->getMediaItemBelongingToContainer($container, $mediaId);

            // Queue the job to delete the media item.
            $job = new DeleteMediaJob($mediaItem, app(MediaService::class));
            dispatch($job);

            return $this->responseService()->json()
                ->setReturnObject($mediaItem->toArray(), 'Media', 'deleted')
                ->setResponseCode(202)
                ->render();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return $this->responseService()->json()
                ->resourceNotFound();
        }
    }
}
