<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'bail|required',
            'parent_id' => 'bail|required|regex:/^[a-f\d]{24}$/i'
        ], [
            'required' => ':attribute không được để trống',
            'regex' => ':attribute không phải là Object Id',
        ]);
        $data = $request->only([
            'name',
            'parent_id'
        ]);
        $category = $this->categoryService->create($data);
        return response()->json($category->toArray());
    }
}


