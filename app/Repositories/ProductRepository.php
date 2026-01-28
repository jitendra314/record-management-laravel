<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    /**
     * Paginate products with optional search
     */
    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        return Product::query()

            ->when($filters['search'], function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%');
            })

            ->when($filters['min_price'], function ($q) use ($filters) {
                $q->where('price', '>=', $filters['min_price']);
            })

            ->when($filters['max_price'], function ($q) use ($filters) {
                $q->where('price', '<=', $filters['max_price']);
            })

            ->when($filters['from_date'], function ($q) use ($filters) {
                $q->whereDate('available_at', '>=', $filters['from_date']);
            })

            ->when($filters['sort'], function ($q) use ($filters) {
                match ($filters['sort']) {
                    'latest'     => $q->orderByDesc('created_at'),
                    'price_asc'  => $q->orderBy('price'),
                    'price_desc' => $q->orderByDesc('price'),
                    default      => null
                };
            })

            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Create product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update product
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    /**
     * Delete product
     */
    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function bulkDelete(array $ids): int
    {
        return Product::whereIn('id', $ids)->delete();
    }
}
