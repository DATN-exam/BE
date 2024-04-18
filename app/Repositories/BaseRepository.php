<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update(Model $model, $attributes = [])
    {
        return $model->update($attributes);
    }

    public function updateById($id, $attributes = [])
    {
        return $this->model->find($id)->update($attributes);
    }

    public function updateMany($ids, $attributes = [])
    {
        return $this->model->whereIn('id', $ids)->update($attributes);
    }

    public function delete($id)
    {
        return $this->model->whereIn('id', $id)->delete();
    }

    public function insert($data = [])
    {
        return $this->model->insert($data);
    }
}
