<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\ListCategoryRequest;
use App\Http\Requests\Category\ProductsByCategoryRequest;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * List all categories.
     */
    public function index(ListCategoryRequest $request): JsonResponse
    {
        return $this->categoryService->listCategories($request->all());
    }

    /**
     * Get products by categories.
     */
    public function products(ProductsByCategoryRequest $request): JsonResponse
    {
        return $this->categoryService->getProductsByCategories($request->all());
    }

    /**
     * Get suppliers by category.
     */
    public function suppliers(int $categoryId): JsonResponse
    {
        return $this->categoryService->getSuppliersByCategory($categoryId);
    }
}
