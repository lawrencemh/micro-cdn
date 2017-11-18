<?php

namespace App\Http\Controllers\User;

use App\Services\MediaService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class ContainerMediaController extends Controller
{
    /**
     * The container repository instance.
     *
     * @var \App\Repositories\Contracts\ContainerRepositoryInterface
     */
    protected $containerRepository;

    /**
     * The media repository instance.
     *
     * @var \App\Repositories\Contracts\MediaRepositoryInterface
     */
    protected $mediaRepository;

    /**
     * The media service instance.
     *
     * @var \App\Services\MediaService
     */
    protected $mediaService;

    /**
     * ContainerMediaController constructor.
     *
     * @param \App\Repositories\Contracts\ContainerRepositoryInterface $containerRepository
     * @param \App\Repositories\Contracts\MediaRepositoryInterface     $mediaRepository
     * @param \App\Services\MediaService                               $mediaService
     */
    public function __construct(
        ContainerRepositoryInterface $containerRepository,
        MediaRepositoryInterface $mediaRepository,
        MediaService $mediaService
    )
    {
        $this->middleware('auth');

        $this->containerRepository = $containerRepository;
        $this->mediaRepository     = $mediaRepository;
        $this->mediaService        = $mediaService;
    }

    /**
     * Return a JSON list of all the authorised user's media items for the given container.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $containerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $containerId)
    {
        try {
            $container  = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItems = $this->mediaRepository->getAllMediaBelongingToContainer($container);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $containerId, $mediaId)
    {
        try {
            $container = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItem = $this->mediaRepository->getMediaItemBelongingToContainer($container, $mediaId);

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
     * @return string
     */
    public function store(Request $request, $containerId)
    {
        $this->validate($request, [
            'media_item' => 'file|image',
        ]);

        if ($request->hasFile('media_item') && $request->file('media_item')->isValid()) {
            $container = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);

            // call media creation service.
            $mediaItem = $this->mediaService->create($container, $request->file('media_item'))->save();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $containerId, $mediaId)
    {
        try {
            $container = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItem = $this->mediaRepository->getMediaItemBelongingToContainer($container, $mediaId);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $containerId, $mediaId)
    {
        try {
            $container = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItem = $this->mediaRepository->getMediaItemBelongingToContainer($container, $mediaId);

            // Call media update service
            $this->mediaService->delete($mediaItem);

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
