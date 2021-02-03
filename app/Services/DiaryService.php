<?php

namespace App\Services;

use App\Http\Responses\StatusCode;
use App\Repositories\DiaryRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ResponseSuccess;
use App\Http\Responses\ResponseError;
use MongoDB\BSON\ObjectId;

class DiaryService
{
    protected $diaryRepository;

    public function __construct(DiaryRepository $diaryRepository)
    {
        $this->diaryRepository = $diaryRepository;
    }

    public function create($request, $id_post)
    {
        $create = $this->diaryRepository->create([
            'id_user_create_diary' => new ObjectId(Auth::id()),
            'id_post' => new ObjectId($id_post),
            'type' => $request->get('type'),
        ]);
        return (new ResponseSuccess($create, 'Tao nhat ki thanh cong'));
    }

    public function listDiary()
    {
        $array = [];
        $listDiary = $this->diaryRepository->getDiary();
        foreach ($listDiary as $diary) {
            $arr = ['id_diary' => $diary->_id, 'username' => $diary->user['full_name'],
                'type' => $diary->type, 'ip_post' => (string)$diary->id_post, 'created_at' => $diary->created_at];
            array_push($array, $arr);
        }
        return (new ResponseSuccess(['diaries' => $array], 'Lay nhat ky thanh cong'));
    }

    public function delete($id_diary)
    {
        $find = $this->diaryRepository->find(['_id'=> $id_diary,'id_user_create_diary'=>new ObjectId(Auth::id())])->first();
        if (is_null($find)) return (new ResponseError(StatusCode::BAD_REQUEST,'Ban khong the xoa nhat ky'));
        $find->delete();
        return (new ResponseSuccess([],'Xoa nhat ky thanh cong'));
    }
}