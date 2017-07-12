<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ContainerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $container = new \App\Models\Container;
        $container->name = $request->name;
        $container->user()->associate($request->user());
        $container->save();
        
        // Return container attributes & 201 success created response
        return response()->json([
            'data' => array_merge($container->toArray(), [
                'entity_status' => 'created',
                'type' => 'Container',
            ]),
        ], 201);
    }
}
