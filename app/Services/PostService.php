<?php

namespace App\Services;

use App\Http\Responses\StatusCode;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Repositories\PostRepository;
use MongoDB\BSON\ObjectId;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function create($request)
    {
        $create = $this->postRepository->create([
            'id_user' => new ObjectId(Auth::id()),
            'content' => $request->get('content'),
            'photos' => $request->get('photo'),
        ]);

        return (new ResponseSuccess($create, 'tao bai viet thành công!'));
    }

    public function update($request, $id)
    {

        $getDocs = $this->postRepository->where('id_user', new ObjectId(Auth::id()))->first();
        $getId = $getDocs->_id;
        if ($getId === $id) {
            $update = $getDocs->update([
                'id_user' => new ObjectId(Auth::id()),
                'content' => $request->get('content'),
                'photos' => $request->get('photo'),
            ]);
            return (new ResponseSuccess($update, 'Sua bai viet thanh cong!'));

        }
        return (new ResponseError(StatusCode::BAD_REQUEST, 'Khong dung bai post!'));

    }

    public function delete($id)
    {
        $getDocs = $this->postRepository->where('id_user', new ObjectId(Auth::id()))->first();
        $getId = $getDocs->_id;
        if ($getId === $id) {
            $getDocs->delete();
            return (new ResponseSuccess($getDocs, 'Xoa thanh cong!'));
        }
        return (new ResponseError(StatusCode::BAD_REQUEST, 'Khong dung bai post!'));
    }

    public function getPostUser($id_user)
    {
        $array = [];
        $getData = $this->postRepository->getPostByUser($id_user);
//        dd($getData);
        foreach ($getData as $document) {
            $arr = ['content' => $document->content, 'photos' => $document->photos, 'created_at' => $document->created_at, 'username' => $document->user['full_name'],'user_id'=>(string)$document->user['_id']];
            array_push($array, $arr);
        }
        return (new ResponseSuccess(['posts' => $array], 'Thanh cong!'));
    }

    public function getAllPost()

    {
        $array = [];
        $getAllPost = $this->postRepository->getPostByUser();
//        dd($getAllPost);
        foreach ($getAllPost as $document) {
            $arr = ['content' => $document->content, 'photos' => $document->photos, 'created_at' => $document->created_at, 'username' => $document->user['full_name'],'user_id'=>(string)$document->user['_id']];
            array_push($array, $arr);
        }
        return (new ResponseSuccess(['posts' => $array], 'Thanh cong!'));
    }
}