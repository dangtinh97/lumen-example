<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Post;
use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Repositories\NotifiRepository;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Session;
use MongoDB\BSON\ObjectId;

class NotifiService
{
    protected $notification;
    protected $postRepository;

    public function __construct(NotifiRepository $notification, PostRepository $postRepository)
    {
        $this->notification = $notification;
        $this->postRepository = $postRepository;

    }

    public function create($request, $id_post)
    {
        $id_post = new ObjectId($id_post);
        $getPost = $this->postRepository->where('_id', $id_post)->first();
        $getIdUser = $getPost->id_user;
        $create = $this->notification->create([
            'id_user_create_post' => $getIdUser,
            'id_user_create_notification' => $id_post = new ObjectId(Auth::id()),
            'id_post' => $id_post,
            'type' => $request->get('type'),
        ]);
        return (new ResponseSuccess($create, 'Tao thong bao thanh cong'));
    }

    public function listNotification($request)
    {
        $listNotification = $this->notification->where('id_user_create_post', $this->id_user)
            ->whereAnd('id_user_create_notification', '<>', $this->id_user)->get();
        return (new ResponseSuccess($listNotification, 'Lay ban ghi thanh cong'));
    }

    public function delete($request, $id)
    {
        $delete = $this->notification->findById($id);
//        dd($delete);
        if ($request->token) {
            $delete->delete();
            return (new ResponseSuccess($delete, 'Xoa thong bao thanh cong'));
        } else {
            return (new ResponseError('', 'Ban can dang nhap de xoa'));
        }
    }
}