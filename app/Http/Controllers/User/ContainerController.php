<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ContainerService;
use App\Http\Controllers\Controller;

class ContainerController extends Controller
{
    /**
     * The container service instance.
     *
     * @var \App\Services\ContainerService
     */
    protected $containerService;

    /**
     * ContainerController constructor.
     *
     * @param \App\Services\ContainerService $containerService
     * @return void
     */
    public function __construct(
        ContainerService $containerService
    )
    {
        $this->middleware('auth');

        $this->containerService = $containerService;
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
        $containers = $request->user()->containers->toArray();
        
        return response()->json($containers, 200);
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
            $container = $request->user()->containers()->findOrFail($containerId)->toArray();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return response()->json([
                'errors' => [
                    'Resource not found',
                ],
            ], 404);

        }

        return response()->json([
            'data' => array_merge($container, [
                'entity_status' => 'exists',
                'type' => 'Container',
            ]),
        ], 200);
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
        return response()->json([
            'data' => array_merge($container->toArray(), [
                'entity_status' => 'created',
                'type' => 'Container',
            ]),
        ], 201);
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
            $container = $request->user()->containers()->findOrFail($containerId);

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
            $container->name = $request->name;
            $container->save();

            return response()->json([
                'data' => array_merge($container->toArray(), [
                    'entity_status' => 'exists',
                    'type' => 'Container',
                ]),
            ], 202);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return response()->json([
                'errors' => [
                    'Resource not found',
                ],
            ], 404);

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
            $container = $request->user()->containers()->findOrFail($containerId);

            // Delete the container
            $container->delete();

            return response()->json([
                'data' => array_merge($container->toArray(), [
                    'entity_status' => 'deleted',
                    'type' => 'Container',
                ]),
            ], 202);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // Resource not found or not owned by authorised user
            return response()->json([
                'errors' => [
                    'Resource not found',
                ],
            ], 404);

        }

    }
}
