<?php

namespace App\Models;

use App\Traits\SoftDelete\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\Model;

use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use MongoDB\BSON\ObjectId;

class Diary extends Model implements AuthenticatableContract, AuthorizableContract,JWTSubject
{
    protected $collection = 'diaries';
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user_create_diary','id_post','type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
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
    public function getDiary(){
        $option = [
          'typeMap'=>[
              'array'=>'array',
              'document'=>'array',
              'root'=>'array',
          ]
        ];
        $pipeline = [
            [
                '$match'=>[
                    'deleted_flag'=>false,
                    'id_user_create_diary'=>new ObjectId(Auth::id()),
                ]
            ],
            [
                '$lookup'=>[
                    'from'=>'users',
                    'localField'=>'id_user_create_diary',
                    'foreignField'=>'_id',
                    'as'=>'user'
                ]
            ],
            [
                '$project'=>[
                    'id_post'=>1,
                    'type'=>1,
                    'created_at'=>1,
                    'user'=>[
                        '$arrayElemAt'=>['$user',0],
                        ]
                ]
            ]

        ];
        $result = self::raw(function ($collection) use ($pipeline,$option){
           return $collection->aggregate($pipeline,$option);
        });
        return $result;
    }
}
