<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Container;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class ContainerRepository extends AbstractBaseRepository implements ContainerRepositoryInterface
{
    /**
     * The eloquent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * ContainerRepository constructor.
     *
     * @param \App\Models\Container $container
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->model = $container;
    }

    /**
     * Return all containers that belong to the given user.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function getAllContainersBelongingToUser(User $user)
    {
        return $this->where('user_id', '=', $user->id)->get();
    }

    /**
     * Return the container belonging to a given user.
     *
     * @param \App\Models\User $user
     * @param                  $containerId
     *
     * @return mixed
     */
    public function getContainerBelongingToUser(User $user, $containerId)
    {
        return $this->where('user_id', '=', $user->id)->where('id', $containerId)->first();
    }
}
