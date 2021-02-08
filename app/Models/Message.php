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
use MongoDB\BSON\ObjectId;

class Message extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'messages';

    protected $fillable = [
        'conversation_id', 'send_id', 'take_id', 'content'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getData($conversationId, $lastMessageId)
    {
        $pipeline = [
            [
                '$match'=>[
                    'deleted_flag'=>false,
                    'conversation_id'=>new ObjectId($conversationId),
                ]
            ],
            [
                '$project'=>[
                    '_id'=>1,
                    'content'=>1,
                    'created_at'=>1,
                    'send_id'=>1,
                    'take_id'=>1
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
        if(!empty($lastMessageId))
        {
            $pipeline[0]['$match']['_id'] = ['$gte'=>new ObjectId($lastMessageId)];
        }
        $result = self::raw(function ($collection) use ($pipeline, $options){
            return $collection->aggregate($pipeline, $options);
        });
        return $result;
    }

}


