<?php

namespace App\Repositories\Eloquent;

abstract class AbstractBaseRepository
{
    /**
     * The eloquent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The query builder instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $queryBuilder = null;

    /**
     * Retrieve all entities from for the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Create a new entity for the given model.
     *
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $input)
    {
        $model = new $this->model;
        $model->fill($input);
        $model->save();

        return $model;
    }

    /**
     * Delete the given entity.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id)
    {
        return $this->findById($id)->delete();
    }

    /**
     * Find a model by its ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Return the first entity from the query builder.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        return $this->retrieveOrCreateQueryInstance()->firstOrFail();
    }

    /**
     * Retrieve all entities from the query builder for the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->getQueryInstance()->get();
    }

    /**
     * Get the query for the given model with conditions applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQueryInstance()
    {
        return $this->queryBuilder;
    }

    /**
     * Get the query builder instance or create a new instance.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function retrieveOrCreateQueryInstance()
    {
        return $this->queryBuilder ?? $this->queryBuilder = new $this->model;
    }

    /**
     * Update the given model's attributes.
     *
     * @param int   $id
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateById(int $id, array $input)
    {
        $model = $this->findById($id);
        $model->fill($input);
        $model->save();

        return $model;
    }

    /**
     * Add a where condition to the query builder.
     *
     * @param        $column
     * @param null   $operator
     * @param null   $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->queryBuilder = $this->retrieveOrCreateQueryInstance()->where($column, $operator, $value, $boolean);

        return $this;
    }

}
