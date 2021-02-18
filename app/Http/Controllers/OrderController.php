<?php
namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $list = $this->orderService->listOrder($request);
        return response()->json($list->toArray());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'bail|required|regex:/^[a-f\d]{24}$/i|exists:products,_id',
            'amount' => 'bail|required|integer'
        ], [
            'required' => ':attribute không được để trống',
            'regex' => ':attribute phải là dạng là Object Id',
            'exists' => ':attribute phải có trong bảng products',
            'integer' => ':attribute phải là số nguyên'
        ]);
        $data = $request->only([
            'product_id',
            'amount',
        ]);
        $order = $this->orderService->create($data);
        return response()->json($order->toArray());
    }
}


