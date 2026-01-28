<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Paginate with search
     */
    public function paginateWithFilters(Request $request): LengthAwarePaginator
    {
        return $this->productRepository->paginateWithFilters([
            'search'     => $request->search,
            'min_price'  => $request->min_price,
            'max_price'  => $request->max_price,
            'from_date'  => $request->from_date,
            'sort'       => $request->sort,
        ]);
    }

    /**
     * Create product
     */
    public function create(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update product
     */
    public function update(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }

    /**
     * Delete product
     */
    public function delete(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }

    public function bulkDelete(array $ids): int
    {
        return $this->productRepository->bulkDelete($ids);
    }
}
