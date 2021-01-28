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
    public function delete(Request $request, $id){
        $delete = $this->postService->delete($request, $id);
        return response()->json($delete->toArray());
    }
    public function getPost_UserLogin(){
        $getPost_UserLogin = $this->postService->getPost_UserLogin();
        return response()->json($getPost_UserLogin->toArray());
    }
    public function getAllPost(){
        $getAllPost = $this->postService->getAllPost();
        return response()->json($getAllPost->toArray());
    }
}