<?php
namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function getProduct($categoryId, $lastProductId)
    {
        return $this->model->getData($categoryId, $lastProductId);
    }
}
