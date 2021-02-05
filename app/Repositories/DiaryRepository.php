<?php


namespace App\Repositories;


use App\Models\Diary;
use Jenssegers\Mongodb\Eloquent\Model;

class DiaryRepository extends BaseRepository
{
    public function __construct(Diary $model)
    {
        $this->model = $model;
    }

    public function diaryOfUser($id)
    {
        return $this->model->getData($id);
    }
}

