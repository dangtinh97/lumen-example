<?php


namespace App\Repositories;


use App\Models\Comment;
use Jenssegers\Mongodb\Eloquent\Model;

class CommentRepository extends BaseRepository
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function getCommentsOfPost($postId, $lastCommentId)
    {
        return $this->model->getData($postId, $lastCommentId);
    }
}
