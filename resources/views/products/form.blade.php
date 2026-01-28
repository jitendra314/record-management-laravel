@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
    <div class="container">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>
                {{ isset($product) ? 'Edit Product' : 'Add Product' }}
            </h2>

            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                ← Back to Products
            </a>
        </div>

        {{-- Card --}}
        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST"
                    action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}">

                    @csrf
                    @isset($product)
                        @method('PUT')
                    @endisset

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $product->title ?? '') }}">

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <div id="editor" class="form-control @error('description') is-invalid @enderror">
                            {!! old('description', $product->description ?? '') !!}</div>
                        <input type="hidden" name="description" id="description">

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        {{-- Price --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" name="price" step="0.01"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->price ?? '') }}">

                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Available At --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available At</label>
                            <input type="date" name="available_at"
                                class="form-control @error('available_at') is-invalid @enderror"
                                value="{{ old('available_at', isset($product) ? $product->available_at->format('Y-m-d') : '') }}">

                            @error('available_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>

                        <button class="btn btn-primary">
                            {{ isset($product) ? 'Update Product' : 'Create Product' }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow'
        });

        const hiddenInput = document.getElementById('description');

        function syncDescription() {
            let html = quill.root.innerHTML.trim();

            // Quill empty cases
            if (
                html === '<p><br></p>' ||
                html === '<div><br></div>' ||
                quill.getText().trim().length === 0
            ) {
                hiddenInput.value = '';
            } else {
                hiddenInput.value = html;
            }
        }

        // Initial sync (important for edit page)
        syncDescription();

        // Update on every change
        quill.on('text-change', function() {
            syncDescription();
        });
    </script>



@endsection
