<?php

namespace App\Services;

use App\Http\Responses\ResponseSuccess;
use App\Repositories\CategoryRepository;
use MongoDB\BSON\ObjectId;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function create(array $data)
    {
        $category = $this->categoryRepository->create([
            'name' => $data['name'],
            'parent_id' => new ObjectId($data['parent_id'])
        ]);
        return (new ResponseSuccess($category, 'Tạo loại sản phẩm thành công'));
    }
}


