<?php

namespace App\Services;

use App\Http\Responses\ResponseSuccess;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\ObjectId;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function listOrder($request)
    {
        $listOrder = $this->orderRepository->getOrderOfUser($request->get('user_id'), $request->get('last_order_id'));
        return (new ResponseSuccess($listOrder, 'List order:'));
    }

    public function create(array $data)
    {
        $order = $this->orderRepository->create([
            'product_id' => new ObjectId($data['product_id']),
            'user_id' => Auth::id(),
            'amount' => $data['amount'],
        ]);
        return (new ResponseSuccess($order, 'Tạo order thành công'));
    }
}


