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

class Conversation extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'conversations';

    protected $fillable = [
        'conversation_id', 'admin_id','join_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getIdConversation($id)
    {
        $pipeline = [
            [
                '$match'=>[
                    '$or' => [
                        [
                            '$and' => [
                                [
                                    'admin_id' => new ObjectID(Auth::id())
                                ],
                                [
                                    'join_id' => new ObjectID($id)
                                ]
                            ]
                        ],
                        [
                            '$and' => [
                                [
                                    'admin_id' => new ObjectID($id)
                                ],
                                [
                                    'join_id' => new ObjectID(Auth::id())
                                ]
                            ]
                        ]
                    ]
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


