<?php


namespace App\Services;


use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Models\Comment;
use App\Models\Diary;
use App\Repositories\CommentRepository;
use App\Repositories\DiaryRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PostRepository;
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
    protected $notificationRepository;
    protected $postRepository;

    public function __construct(CommentRepository $commentRepository, DiaryRepository $diaryRepository, NotificationRepository $notificationRepository, PostRepository $postRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->diaryRepository = $diaryRepository;
        $this->notificationRepository = $notificationRepository;
        $this->postRepository = $postRepository;
    }

    public  function listComment($request)
    {
        $listComment = $this->commentRepository->getCommentsOfPost($request->get('post_id'), $request->get('last_comment_id'));
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
        $post = $this->postRepository->findById($request->get('post_id'));
        $this->notificationRepository->create([
            'user_id_create_post'=>new ObjectId($post->user_id),
            'post_id'=>new ObjectId($request->get('post_id')),
            'user_id'=>new ObjectId(Auth::id()),
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

