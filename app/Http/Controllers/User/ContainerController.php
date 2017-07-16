<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ContainerService;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class ContainerController extends Controller
{
    /**
     * The container repository instance.
     *
     * @var \App\Repositories\Contracts\ContainerRepositoryInterface
     */
    protected $containerRepository;

    /**
     * The container service instance.
     *
     * @var \App\Services\ContainerService
     */
    protected $containerService;

    /**
     * ContainerController constructor.
     *
     * @param \App\Repositories\Contracts\ContainerRepositoryInterface $containerRepository
     * @param \App\Services\ContainerService $containerService
     * @return void
     */
    public function __construct(
        ContainerRepositoryInterface $containerRepository,
        ContainerService $containerService
    )
    {
        $this->middleware('auth');

        $this->containerRepository = $containerRepository;
        $this->containerService = $containerService;
        parent::boot();
    }

    /**
     * Return a JSON list of all the authorised user's containers.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get all the users containers in array format
        $containers = $this->containerRepository->getAllContainersBelongingToUser($request->user());

        return $this->responseService->json()
            ->setReturnObject($containers->toArray(), 'Container')
            ->render();
    }

    /**
     * Return a JSON list of the given container's attributes.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $containerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $containerId)
    {
        try {
            // Get the given container belonging to the user
            $container = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);

            return $this->responseService->json()
                ->setReturnObject($container->toArray(), 'Container')
                ->render();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return $this->responseService->json()
                ->resourceNotFound();

        }
    }
    
    /**
     * Store a new Container for the authorised user in the database.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate create container request
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('containers')->where(function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                }),
            ],
        ]);
        
        // Create new container
        $container = $this->containerService->create()
            ->setName($request->name)
            ->setOwner($request->user())
            ->save();

        // Return container attributes & 201 success created response
        return $this->responseService->json()
            ->setReturnObject($container->toArray(), 'Container')
            ->setResponseCode(201)
            ->render();
    }

    /**
     * Update the given user's container in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $containerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $containerId)
    {
        try {
            // Get the given container belonging to the user
            $container = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);

            // Validate create container request
            $this->validate($request, [
                'name' => [
                    'required',
                    Rule::unique('containers')->where(function ($query) use ($request) {
                        $query->where('user_id', $request->user()->id);
                    })->ignore($container->id, 'id'),
                ],
            ]);

            // Update the container
            $container = $this->containerService->update($container)
                ->setName($request->name)
                ->save();

            return $this->responseService->json()
                ->setReturnObject($container->toArray(), 'Container')
                ->setResponseCode(202)
                ->render();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return $this->responseService->json()
                ->resourceNotFound();

        }
    }

    /**
     * Delete the given user's container from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $containerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $containerId)
    {
        try {
            // Get the given container belonging to the user
            $container = $this->containerRepository->getContainerBelongingToUser($request->user(), $containerId);

            // Delete the container
            $this->containerService->delete($container);

            return $this->responseService->json()
                ->setReturnObject($container->toArray(), 'Container', 'deleted')
                ->setResponseCode(202)
                ->render();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return $this->responseService->json()
                ->resourceNotFound();

        }

    }
}
