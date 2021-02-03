<?php

namespace App\Services;

use App\Http\Responses\StatusCode;
use App\Repositories\DiaryRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Repositories\NotifiRepository;
use App\Repositories\PostRepository;
use MongoDB\BSON\ObjectId;

class NotifiService
{
    protected $notification;
    protected $postRepository;
    protected $diaryRepository;

    public function __construct(NotifiRepository $notification, PostRepository $postRepository, DiaryRepository $diaryRepository)
    {
        $this->notification = $notification;
        $this->postRepository = $postRepository;
        $this->diaryRepository = $diaryRepository;
    }

    public function create($request, $id_post)
    {
        $id_post = new ObjectId($id_post);
        $getPost = $this->postRepository->where('_id', $id_post)->first();
        $getIdUser = $getPost->id_user;
//        dd($getIdUser);
        if ((string)$getIdUser === Auth::id()) {
            $create = $this->diaryRepository->create([
                'id_user_create_diary' => new ObjectId(Auth::id()),
                'id_post' => new ObjectId($id_post),
                'type' => $request->get('type'),
            ]);
            return (new ResponseSuccess($create, 'Tao thong bao that bai, tao nhat ki thanh cong'));
        }
        $create = $this->notification->create([
            'id_user_create_post' => $getIdUser,
            'id_user_create_notification' => new ObjectId(Auth::id()),
            'id_post' => $id_post,
            'type' => $request->get('type'),
        ]);
        return (new ResponseSuccess($create, 'Tao thong bao thanh cong'));

    }

    public function listNotification()
    {
        $array = [];
        $getUser = $this->notification->getUser();
//        dd($getUser);
        foreach ($getUser as $document) {
            $arr = ['id_notification' => $document->_id, 'created_at' => $document->created_at,
                'username' => $document->user['full_name'], 'user_id' => (string)$document->user['_id'], 'type' => $document->type, 'id_post' => (string)$document->id_post,];
            array_push($array, $arr);
        }
        return (new ResponseSuccess(['notifications' => $array], 'Lay ban ghi thanh cong'));
    }

    public function delete($id_notification)
    {
        $find = $this->notification->find(['_id'=> $id_notification,'id_user_create_post'=>new ObjectId(Auth::id())])->first();
        if (is_null($find)) return (new ResponseError(StatusCode::BAD_REQUEST,'Ban khong the xoa thong bao'));
        $find->delete();
        return (new ResponseSuccess([],'Xoa thong bao thanh cong'));

    }

}