<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $list = $this->productService->listProduct($request->get('category_id'), $request->get('last_product_id'));
        return response()->json($list->toArray());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'bail|required',
            'category_id' => 'bail|required|regex:/^[a-f\d]{24}$/i|exists:categories,_id',
            'amount' => 'bail|required|integer'
        ], [
            'required' => ':attribute không được để trống',
            'regex' => ':attribute phải là dạng Object Id',
            'exists' => ':attribute phải có trong bảng categories',
            'integer' => ':attribute phải là số nguyên'
        ]);
        $data = $request->only([
            'title',
            'category_id',
            'amount'
        ]);
        $product = $this->productService->create($data);
        return response()->json($product->toArray());
    }
}

