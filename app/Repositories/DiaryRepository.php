<?php
namespace App\Repositories;

use App\Models\Diary;
use Jenssegers\Mongodb\Eloquent\Model;

class DiaryRepository extends BaseRepository{
    public function __construct(Diary $model)
    {
//        parent::__construct($model);
        $this->model = $model;
    }
    public function getDiary(){
        return $this->model->getDiary();
    }
}