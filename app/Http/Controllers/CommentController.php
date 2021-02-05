<?php

namespace App\Http\Controllers;


use App\Http\Responses\ResponseSuccess;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $this->validate($request,[
            'post_id'=>'required|exists:posts,_id'
        ],[
            'required' => ':attribute không được để trống',
            'exists'=> ':attribute phải có trong bảng post'
        ]);
        $list = $this->commentService->listComment($request);
        return response()->json($list->toArray());
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'post_id'=>'required|exists:posts,_id',
            'content'=>'required'
        ],[
            'required' =>':attribute không được để trống',
            'exists'=>':attribute phải có trong bảng post'
        ]);
        $create = $this->commentService->createComment($request);
        return response()->json($create->toArray());
    }

    public function update(Request $request,$id)
    {
        $request = new Request(array_merge($request->all(),['comment_id'=>$id]));
        $this->validate($request, [
            'content'=>'required',
            'comment_id'=>'required|exists:comments,_id',
        ],[
            'required' => ':attribute không được để trống',
            'exists'=>':attribute phải có trong bảng comments'
        ]);
        $update = $this->commentService->updateComment($id, $request);
        return response()->json($update->toArray());
    }

    public function destroy(Request $request, $id)
    {
        $request = new Request(array_merge($request->all(),['comment_id'=>$id]));
        $this->validate($request, [
            'comment_id'=>'required|exists:comments,_id'
        ],[
            'exists'=>':attribute phải có trong bảng comments'
        ]);
        $delete = $this->commentService->deleteComment($id);
        return response()->json($delete->toArray());
    }
}

