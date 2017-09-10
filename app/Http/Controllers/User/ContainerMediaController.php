<?php

namespace App\Http\Controllers\User;

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
     * ContainerMediaController constructor.
     *
     * @param \App\Repositories\Contracts\ContainerRepositoryInterface $containerRepository
     * @param \App\Repositories\Contracts\MediaRepositoryInterface     $mediaRepository
     */
    public function __construct(
        ContainerRepositoryInterface $containerRepository,
        MediaRepositoryInterface $mediaRepository
    )
    {
        $this->middleware('auth');

        $this->containerRepository = $containerRepository;
        $this->mediaRepository     = $mediaRepository;
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
            $container  = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);
            $mediaItems = $this->mediaRepository->getMediaItemBelongingToContainer($container, $mediaId);

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
     * Create a new media item for the given container.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $containerId
     * @return string
     * @todo implement
     */
    public function store(Request $request, $containerId)
    {
        return json_encode($request);
    }

    public function update(Request $request, $containerId, $mediaId)
    {
        // @todo
    }

    public function destroy(Request $request, $containerId, $mediaId)
    {
        // @todo
    }
}
