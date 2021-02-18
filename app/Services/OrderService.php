<?php

namespace App\Services;

use App\Http\Responses\ResponseError;
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

    public function listOrder($userId, $lastOrderId)
    {
        $listOrder = $this->orderRepository->getOrderOfUser($userId, $lastOrderId);
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

    public function update(array $data, $id)
    {
        $order = $this->orderRepository->findById($id);
        $userIdOrder = $order->user_id->__toString();
        if ($userIdOrder !== Auth::id()) return (new ResponseError('500', 'Update order không thành công'));
        $order->update([
            'product_id' => $data['product_id'],
            'amount' => $data['amount']
        ]);
        return (new ResponseSuccess($order, 'Update order thành công'));
    }

    public function delete($id)
    {
        $order = $this->orderRepository->findById($id);
        $userIdOrder = $order->user_id->__toString();
        if ($userIdOrder !== Auth::id()) return (new ResponseError(500, 'Xóa order không thành công'));
        $order->delete();
        return (new ResponseSuccess($order, 'Xóa order thành công'));
    }
}


