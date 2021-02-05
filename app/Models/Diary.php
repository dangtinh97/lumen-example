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

class Diary extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'diaries';

    protected $fillable = [
        'user_id', 'post_id','type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getData($id)
    {
        $pipeline = [
            [
                '$match'=>[
                    'deleted_flag'=>false,
                    'user_id'=>new ObjectId($id)
                ]
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
                    'post_id'=>1,
                    'type'=>1,
                    'created_at'=>1,
                    'user'=>[
                        '$arrayElemAt'=>['$user.name', 0]
                    ]
                ]
            ],[
                '$sort'=>[
                    'created_at'=>1
                ]
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

