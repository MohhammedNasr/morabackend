<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index(ListProductRequest $request): JsonResponse
    {
        return $this->productService->getProducts($request->all());
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        return $this->productService->createProduct($request->all());
    }

    public function show(Product $product): JsonResponse
    {
        return $this->productService->getProductDetails($product);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        return $this->productService->updateProduct($request->all(), $product);
    }

    public function destroy(Product $product): JsonResponse
    {
        return $this->productService->deleteProduct($product);
    }

    public function suppliers(Product $product): JsonResponse
    {
        return $this->productService->getProductSuppliers($product);
    }
}
