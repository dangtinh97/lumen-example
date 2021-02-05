<?php


namespace App\Repositories;


use Jenssegers\Mongodb\Eloquent\Model;

class BaseRepository
{
    public $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model::all();
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function findById($id)
    {
        return $this->model::find($id);
    }

    public function findWhere($array)
    {
        return $this->model->where($array)->get();
    }

    public  function findByCondition($array)
    {
        return $this->model->orWhere($array)->get();
    }
}
