<?php


namespace App\Repositories;


use App\Models\Post;
use Jenssegers\Mongodb\Eloquent\Model;

class PostRepository extends BaseRepository
{
    public function __construct(Post $model)
    {
//        parent::__construct($model);
        $this->model= $model;
    }
    public function getPostByUser($id_user=null){
        return $this->model->getPostByUser($id_user);
    }
}
