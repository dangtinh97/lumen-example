<?php

namespace App\Services;

use App\Http\Responses\StatusCode;
use function Couchbase\defaultDecoder;
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

        $getDocs = $this->postRepository->where('_id', new ObjectId($id))->first();
        $getUserId = $getDocs->id_user->__toString();
//        dd($getUserId);
        if ($getUserId === Auth::id()) {
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
        $find = $this->postRepository->find(['_id'=>$id,'id_user'=>new ObjectId(Auth::id())])->first();
        if(is_null($find))   return (new ResponseError(StatusCode::BAD_REQUEST, 'Ban khong the xoa bai post!'));
        $find->delete();
        return (new ResponseSuccess($find, 'Xoa bai post thanh cong!'));

    }

    public function getPostUser($id_user)
    {
        $array = [];
        $getData = $this->postRepository->getPostByUser($id_user);
        foreach ($getData as $document) {
            $arr = ['content' => $document->content, 'photos' => $document->photos, 'created_at' => $document->created_at, 'username' => $document->user['full_name'], 'user_id' => (string)$document->user['_id']];
            array_push($array, $arr);
        }
        return (new ResponseSuccess(['posts' => $array], 'Thanh cong!'));
    }

    public function getAllPost()

    {
        $array = [];
        $getAllPost = $this->postRepository->getPostByUser();
//        $getAllPost->take(2);
//        {
//            {
//                $getAllPost->links();
//            }
//        }
        foreach ($getAllPost as $document) {
            $arr = ['content' => $document->content, 'photos' => $document->photos, 'created_at' => $document->created_at, 'username' => $document->user['full_name'],'user_id'=>(string)$document->user['_id']];
            array_push($array, $arr);
        }
        return (new ResponseSuccess($array, 'Thanh cong!'));
    }
}