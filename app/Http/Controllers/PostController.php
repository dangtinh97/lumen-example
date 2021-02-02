<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
class PostController extends Controller{
    protected $postService ;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    public function create(Request $request){
        $this->validate($request,[
            'content'=>'nullable',
            'photo'=>'nullable',
        ]);
        $create = $this->postService->create($request);
        return response()->json($create->toArray());
    }
    public function update(Request $request,$id){
        $this->validate($request,[
            'content'=>'nullable',
            'photo'=>'nullable',

        ]);
        $update = $this->postService->update($request,$id);
        return response()->json($update->toArray());
    }
    public function delete($id){
        $delete = $this->postService->delete($id);
        return response()->json($delete->toArray());
    }
    public function getPostUser($id_user){
        $getPost_UserLogin = $this->postService->getPostUser($id_user);
        return response()->json($getPost_UserLogin->toArray());
    }
    public function getAllPost(){
        $getAllPost = $this->postService->getAllPost();
        return response()->json($getAllPost->toArray());
    }
}