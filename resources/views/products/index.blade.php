@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Products</h4>

                <div>
                    @can('deleteAny', App\Models\Product::class)
                        <button id="bulk-delete-btn" form="bulk-delete-form" class="btn btn-danger me-2 d-none"
                            onclick="return confirm('Delete selected products?')">
                            Delete Selected
                        </button>
                    @endcan


                    @can('create', App\Models\Product::class)
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            + Add Product
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search products..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <input type="number" name="min_price" class="form-control" placeholder="Min price"
                                value="{{ request('min_price') }}">
                        </div>

                        <div class="col-md-2">
                            <input type="number" name="max_price" class="form-control" placeholder="Max price"
                                value="{{ request('max_price') }}">
                        </div>

                        <div class="col-md-2">
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-2">
                            <select name="sort" class="form-select">
                                <option value="">Sort by</option>
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>
                                    Latest
                                </option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                    Price: Low → High
                                </option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                    Price: High → Low
                                </option>
                            </select>
                        </div>

                        <div class="col-md-1 d-grid">
                            <button class="btn btn-outline-primary">Filter</button>
                        </div>

                        <div class="col-md-1 d-grid">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <form id="bulk-delete-form" method="POST" action="{{ route('products.bulk-delete') }}">
                    @csrf
                    @method('DELETE')

                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>#</th>
                                <th>Title</th>
                                <th width="120">Price</th>
                                <th width="160">Available At</th>
                                <th width="180" class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $sr = ($products->currentPage() - 1) * $products->perPage();
                            @endphp

                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $product->id }}"
                                            class="row-checkbox">
                                    </td>

                                    <td>{{ ++$sr }}</td>

                                    <td><strong>{{ $product->title }}</strong></td>

                                    <td>₹ {{ number_format($product->price, 2) }}</td>

                                    <td>{{ $product->available_at->format('d M Y') }}</td>

                                    <td class="text-end">
                                        @can('update', $product)
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="btn btn-sm btn-outline-warning me-1">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $product)
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deleteSingle({{ $product->id }})">
                                                Delete
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
                <form id="single-delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-end">
            {{ $products->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>

    </div>

    {{-- Select all JS --}}
    <script>
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        function toggleBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            bulkDeleteBtn?.classList.toggle('d-none', checkedCount === 0);
        }

        // Select all
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteButton();
        });

        // Individual checkbox change
        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleBulkDeleteButton);
        });


        function deleteSingle(id) {
            if (!confirm('Delete this product?')) return;

            const form = document.getElementById('single-delete-form');
            form.action = `/products/${id}`;
            form.submit();
        }
    </script>

@endsection
