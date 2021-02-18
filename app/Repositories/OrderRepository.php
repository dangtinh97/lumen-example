<?php
namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function getOrderOfUser($userId, $lastOrderId)
    {
        return $this->model->getData($userId, $lastOrderId);
    }
}
