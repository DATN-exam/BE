<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function all();

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = []);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update(Model $model, $attributes = []);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function updateById($id, $attributes = []);

    /**
     * Update
     * @param array $id
     * @param array $attributes
     * @return mixed
     */
    public function updateMany($ids, $attributes = []);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Insert
     * @param array $data
     * @return mixed
     */
    public function insert($data);
}
