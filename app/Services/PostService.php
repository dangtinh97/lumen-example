<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use MongoDB\BSON\ObjectId;
use phpDocumentor\Reflection\Types\This;

class PostService
{
    protected $postRepository;
    protected $userRepository;


    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function create($request)
    {
        $create = $this->postRepository->create([
            'id_user' => new ObjectId(Auth::id()),
            'content' => $request->get('content'),
            'photos'=>$request->get('photo'),
        ]);

            return (new ResponseSuccess($create, 'tao bai viet thành công!'));
    }

    public function update($request, $id)
    {
        $getDocs = $this->postRepository->where('id_user',new ObjectId(Auth::id()))->first();
        $getId = $getDocs->_id;
        if ($getId === $id){
            $update = $getDocs->update([
                'id_user' => new ObjectId(Auth::id()),
                'content' => $request->get('content'),
                'photos'=>$request->get('photo'),
            ]);
            return (new ResponseSuccess($update, 'Sua bai viet thanh cong!'));

        }
        return (new ResponseError('', 'Khong dung bai post!'));

    }

    public function delete($request, $id)
    {
        $getDocs = $this->postRepository->where('id_user',new ObjectId(Auth::id()))->first();
        $getId = $getDocs->_id;
        if ($getId === $id) {
            $getDocs->delete();
            return (new ResponseSuccess($getDocs, 'Xoa thanh cong!'));
        }
            return (new ResponseError('', 'Khong dung bai post!'));

    }

    public function getPost_UserLogin()
    {
        $options = [];
        $pipeline = [
            [
                '$lookup' => [
                    'from' => 'users',
                    'localField' => 'id_user',
                    'foreignField' => '_id',
                    'as' => 'docs'
                ]
            ]
        ];
//        dd($pipeline);
        $cursor = $this->userRepository->aggregate($pipeline, $options);

        foreach ($cursor as $document) {
            return $document['full_name'] . "\n";
        }
//        $getAllPost = $this->postRepository->where('id_user', $this->id_user)->get();
//        return (new ResponseSuccess($getAllPost, 'Thanh cong!'));
    }

    public function getAllPost()
    {
        $getAllPost = $this->postRepository->all();
        return (new ResponseSuccess($getAllPost, 'Lay tat ca ban ghi thanh cong!'));
    }
}