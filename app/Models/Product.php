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

class Product extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'products';

    protected $fillable = [
        'title', 'category_id', 'amount'
    ];

    public function setCategoryIdAttribute($value)
    {
        $this->attributes['category_id'] = new ObjectId($value);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (int)$value;
    }

    public function getData($categoryId, $last_product_id)
    {
        $pipeline = [
            [
                '$match' => [
                    'deleted_flag' => false,
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'categories',
                    'localField' => 'category_id',
                    'foreignField' => '_id',
                    'as' => 'name_category'
                ]
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'title' => 1,
                    'category_id' => 1,
                    'name_category' => [
                        '$arrayElemAt' => ['$name_category.name', 0]
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
        if (!empty($categoryId)) {
            $pipeline[0]['$match']['category_id'] = new ObjectId($categoryId);
        }
        if (!empty($last_product_id)) {
            $pipeline[0]['$match']['_id'] = ['$gte' => new ObjectId($last_product_id)];
        }
        $result = self::raw(function ($collection) use ($pipeline, $options) {
            return $collection->aggregate($pipeline, $options);
        });
        return $result;
    }
}


