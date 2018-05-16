<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    /**
     * Retrieve all entities from for the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Create a new entity for the given model.
     *
     * @param array $input
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $input);

    /**
     * Delete the given entity.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy(int $id);

    /**
     * Find a model by its ID.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id);

    /**
     * Retrieve all entities from the query builder for the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get();

    /**
     * Get the query for the given model with conditions applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQueryInstance();

    /**
     * Update the given model's attributes.
     *
     * @param int   $id
     * @param array $input
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateById(int $id, array $input);

    /**
     * Add a where condition to the query builder.
     *
     * @param        $column
     * @param null   $operator
     * @param null   $value
     * @param string $boolean
     *
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and');
}
