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

class Order extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'orders';

    protected $fillable = [
        'product_id', 'user_id', 'amount'
    ];

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = new ObjectId($value);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (int)($value);
    }

    public function getData($userId, $lastOrderId)
    {
        $pipeline = [
            [
                '$match' => [
                    'deleted_flag' => false,
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'products',
                    'localField' => 'product_id',
                    'foreignField' => '_id',
                    'as' => 'name_product'
                ],
            ],
            [
                '$lookup' => [
                    'from' => 'users',
                    'localField' => 'user_id',
                    'foreignField' => '_id',
                    'as' => 'name_buyer'
                ],
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'product_id' => 1,
                    'user_id' => 1,
                    'amount' => 1,
                    'name_product' => [
                        '$arrayElemAt' => ['$name_product.title', 0]
                    ],
                    'name_buyer' => [
                        '$arrayElemAt' => ['$name_buyer.full_name', 0]
                    ]
                ]
            ],
            [
                '$sort' => [
                    'created_at' => 1
                ]
            ],
            [
                '$limit' => 3
            ]
        ];
        $options = [
            'typeMap' => [
                'array' => 'array',
                'document' => 'array',
                'root' => 'array',
            ]
        ];
        if (!empty($userId)) {
            $pipeline[0]['$match']['user_id'] = $userId;
        }
        if (!empty($lastOrderId)) {
            $pipeline[0]['$match']['_id'] = ['$gte' => new ObjectId($lastOrderId)];
        }
        $result = self::raw(function ($collection) use ($pipeline, $options) {
            return $collection->aggregate($pipeline, $options);
        });
        return $result;
    }
}



