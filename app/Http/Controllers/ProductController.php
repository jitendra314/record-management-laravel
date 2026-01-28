<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class ProductController extends Controller
{
    use AuthorizesRequests;

    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display product list
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->productService->paginateWithFilters($request);

        return view('products.index', compact('products'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->authorize('create', Product::class);

        return view('products.form');
    }

    /**
     * Store product
     */
    public function store(ProductStoreRequest $request)
    {
        $this->authorize('create', Product::class);

        $data = $request->validated();
        $data['description'] = Purifier::clean($data['description']);

        $this->productService->create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Edit product
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return view('products.form', compact('product'));
    }

    /**
     * Update product
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validated();
        $data['description'] = Purifier::clean($data['description']);

        $this->productService->update($product, $data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Single delete (keep existing delete button)
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $this->authorize('deleteAny', Product::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        $deletedCount = $this->productService->bulkDelete($request->ids);

        return redirect()
            ->route('products.index')
            ->with('success', "{$deletedCount} products deleted successfully.");
    }
}
