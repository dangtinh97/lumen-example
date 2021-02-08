<?php


namespace App\Repositories;


use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class PostRepository extends BaseRepository
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }
}

