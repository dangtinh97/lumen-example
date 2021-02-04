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

class Post extends Model implements AuthenticatableContract, AuthorizableContract
{
    protected $collection = 'posts';
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user', 'content', 'photos'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token'
    ];
//    public function user(){
//        return $this->belongsTo(User::class);
//    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPostByUser($id_user)
    {
        $options = [
            'typeMap' => [
                'array' => 'array',
                'document' => 'array',
                'root' => 'array',
            ]
        ];
        $pipeline = [

            [
                '$match' => [
                    'deleted_flag' => false,
//                    '_id'=>new ObjectId($id_user),
                ]
            ],
            [

                '$lookup' => [
                    'from' => 'users',
                    'localField' => 'id_user',
                    'foreignField' => '_id',
                    'as' => 'user'
                ]
            ],
            [
                '$project' => [
                    'content' => 1,
                    'photos' => 1,
                    'created_at' => 1,
                    'user' => [
                        '$arrayElemAt' => ['$user', 0],
                    ]
                ]
            ],
            [
                '$sort' => [
                    '_id' => -1,
                ]
            ],
//            [
//                '$limit'=>2,
//            ],

        ];

        if (!is_null($id_user)) {
            $pipeline[0]['$match']['id_user'] = new ObjectId($id_user);
        }
        $result = self::raw(function ($collection) use ($pipeline, $options) {
            return $collection->aggregate($pipeline, $options);
        });
        return $result;
    }
}
