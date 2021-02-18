<?php
namespace App\Http\Controllers;

use App\Http\Responses\ResponseSuccess;
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
        $this->validate($request,[
            'user_id' => 'regex:/^[a-f\d]{24}$/i',
            'last_order_id' => 'regex:/^[a-f\d]{24}$/i',
        ],
            [
                'regex' => ':attribute phải là dạng Object Id'
            ]);
        $list = $this->orderService->listOrder($request->get('user_id'), $request->get('last_order_id'));
        return response()->json($list->toArray());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'bail|required|regex:/^[a-f\d]{24}$/i|exists:products,_id',
            'amount' => 'bail|required|integer'
        ], [
            'required' => ':attribute không được để trống',
            'regex' => ':attribute phải là dạng Object Id',
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

    public function update(Request $request, $id)
    {
        $request = new Request(array_merge($request->all(), ['order_id' => $id]));
        $this->validate($request, [
            'order_id' => 'bail|required|regex:/^[a-f\d]{24}$/i|exists:orders,_id',
            'product_id' => 'bail|required|regex:/^[a-f\d]{24}$/i|exists:products,_id',
            'amount' => 'bail|required|integer'
        ], [
            'required' => ':attribute không được để trống',
            'regex' => ':attribute phải là dạng Object Id',
            'order_id.exists' => ':attribute phải có trong bảng orders',
            'product_id.exists' => ':attribute phải có trong bảng products',
            'integer' => ':attribute phải là số nguyên'
        ]);
        $data = $request->only([
            'product_id',
            'amount',
        ]);
        $order = $this->orderService->update($data, $id);
        return response()->json($order->toArray());
    }

    public function destroy(Request $request, $id)
    {
        $request = new Request(array_merge($request->all(), ['order_id' => $id]));
        $this->validate($request, [
            'order_id' => 'bail|required|regex:/^[a-f\d]{24}$/i|exists:orders,_id'
        ],
        [
            'required' => ':attribute không được để trống',
            'regex' => ':attribute phải là dạng Object Id',
            'exists' => ':attribute phải có trong bảng orders'
        ]);
        $order = $this->orderService->delete($id);
        return response()->json($order->toArray());
    }
}


