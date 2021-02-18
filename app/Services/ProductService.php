<?php
namespace App\Services;

use App\Http\Responses\ResponseSuccess;
use App\Repositories\ProductRepository;
use MongoDB\BSON\ObjectId;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function listProduct($categoryId, $lastProductId)
    {
        $listProduct = $this->productRepository->getProduct($categoryId, $lastProductId);
        return (new  ResponseSuccess($listProduct, 'List product:'));
    }

    public function create(array $data)
    {
        $product = $this->productRepository->create([
            'title' => $data['title'],
            'category_id' => new ObjectId($data['category_id']),
            'amount' => $data['amount']
        ]);
        return (new ResponseSuccess($product, 'Tạo sản phẩm thành công!'));
    }
}


