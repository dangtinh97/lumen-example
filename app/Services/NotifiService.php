<?php

namespace App\Services;

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
//        $listNotification = $this->notification->where('id_user_create_post', new ObjectId(Auth::id()))
//            ->whereAnd('id_user_create_notification', '<>', new ObjectId(Auth::id()))->get();
        return (new ResponseSuccess(['notifications' => $array], 'Lay ban ghi thanh cong'));
    }

    public function delete($id_notification)
    {
        $array = [];
        $getUser = $this->notification->getUser();
//        dd($id_notification);
        foreach ($getUser as $document) {
            $arr = $document->_id;
            array_push($array, $arr);
        }
        if (in_array($id_notification, $array)) {
            $delete = $this->notification->where('_id', $id_notification)->first();
            $delete->delete();
            return (new ResponseSuccess($delete, 'Xoa thong bao thanh cong'));
        }
        return (new ResponseError('', 'Xoa thong bao that bai'));

    }

}