<?php

namespace App\Services;

abstract class AbstractBaseService
{
    /**
     * The repository instance.
     *
     * @var \App\Repositories\Contracts\BaseRepositoryInterface
     */
    protected $repository;

    /**
     * Retrieve all entities from the repository.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->repository->all();
    }

    /**
     * Create a new entity using the repository instance.
     *
     * @param array $input
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $input)
    {
        return $this->repository->create($input);
    }

    /**
     * Delete the given entity id using the repository.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy(int $id)
    {
        return $this->repository->destroy($id);
    }

    /**
     * Retrieve a model in the repository by its id.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Retrieve the query instance for from the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQueryInstance()
    {
        return $this->repository->getQueryInstance();
    }

    /**
     * Update model by id.
     *
     * @param int   $id
     * @param array $input
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateById(int $id, array $input)
    {
        return $this->repository->updateById($id, $input);
    }
}
