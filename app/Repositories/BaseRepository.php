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
//    public function update($data){
//        return $this->model::update($data);
//    }
    public function all(){
        return $this->model::all();
    }
    public function delete(){
        return $this->model::delete();
    }
    public function where($id,$id_user){
        return $this->model::where($id,$id_user);
    }
    public function findById($id){
        return $this->model::find($id);
    }
    public function first(){
        return $this->model::first();
    }
//    public function get(){
//        return $this->model::get(['_id']);
//    }
    public function get(){
        return $this->model::get();
    }
//    public function getData($id){
//        return $this->model::;
//    }

}
