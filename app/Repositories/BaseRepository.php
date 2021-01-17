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

    public function create($data){
        return $this->model::create($data);
    }
}
