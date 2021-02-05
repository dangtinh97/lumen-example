<?php


namespace App\Services;


use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Models\Comment;
use App\Models\Diary;
use App\Repositories\CommentRepository;
use App\Repositories\DiaryRepository;
use http\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\BSON\ObjectId;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\Self_;

class CommentService
{
    protected $commentRepository;
    protected $diaryRepository;

    public function __construct(CommentRepository $commentRepository, DiaryRepository $diaryRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->diaryRepository = $diaryRepository;
    }

    public  function listComment($request)
    {
        $listComment = $this->commentRepository->getCommentsOfPost($request->get('post_id'));
        return (new ResponseSuccess($listComment, 'List comment of post:'));
    }

    public function createComment($request)
    {
        $comment = $this->commentRepository->create([
            'post_id'=>new ObjectId($request->get('post_id')),
            'user_id'=>new ObjectId(Auth::id()),
            'content'=>$request->get('content')
        ]);
        $this->diaryRepository->create([
            'user_id'=>new ObjectId(Auth::id()),
            'post_id'=>new ObjectId($request->get('post_id')),
            'type'=>'comment'
        ]);
        return (new ResponseSuccess($comment,'Tạo comment thành công!'));
    }

    public function updateComment($id, $request)
    {
        $comment = $this->commentRepository->findById($id);
        $userIdComment = $comment->user_id->__toString();
        if ($userIdComment !== Auth::id()) return (new ResponseError(500, 'Update comment không thành công'));
        $comment->update([
            'content'=>$request->get('content'),
        ]);
        return (new ResponseSuccess($comment, 'Update comment thành công'));
    }

    public function deleteComment($id)
    {
        $comment = $this->commentRepository->findById($id);
        $userIdComment = $comment->user_id->__toString();
        if ($userIdComment !== Auth::id()) return (new ResponseError(500, 'Xóa comment không thành công'));
        $comment->delete();
        return (new ResponseSuccess($comment, 'Xóa comment thành công'));
    }
}

