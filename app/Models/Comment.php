<?php
namespace App\Models;

use App\Traits\SoftDelete\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Jenssegers\Mongodb\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use MongoDB\BSON\ObjectId;
use PhpParser\Node\Expr\Cast\Object_;

class Comment extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'comments';

    protected $fillable = [
        'post_id', 'user_id','content'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getData($postId, $lastCommentId)
    {
        $match1 = [
            'deleted_flag'=>false,
            'post_id'=>new ObjectId($postId)
        ];
        $match2 = [
            'deleted_flag'=>false,
            'post_id'=>new ObjectId($postId),
            '_id'=>['$gte'=>new ObjectId($lastCommentId)]
        ];
        if (empty($lastMessageId))
        {
            $match = $match1;
        }
        else $match = $match2;
        $pipeline = [
            [
                '$match'=>$match
            ],
            [
                '$lookup'=>[
                    'from'=>'users',
                    'localField'=>'user_id',
                    'foreignField'=>'_id',
                    'as'=>'user'
                ]
            ],
            [
                '$project'=>[
                    '_id'=>0,
                    'content'=>1,
                    'created_at'=>1,
                    'updated_at'=>1,
                    'user'=>[
                        '$arrayElemAt'=> ['$user.full_name', 0]
                    ]
                ]
            ],
            [
                '$sort'=>[
                    'created_at'=>-1
                ]
            ],
            [
                '$limit'=>3
            ]
        ];
        $options = [
            'typeMap'=>[
                'array'=>'array',
                'document'=>'array',
                'root'=>'array',
            ]
        ];
        $result = self::raw(function ($collection) use ($pipeline, $options){
            return $collection->aggregate($pipeline, $options);
        });
        return $result;
    }
}

