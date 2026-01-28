@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Create Product')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">
            {{ isset($product) ? 'Edit Product' : 'Create Product' }}
        </h5>

        <form method="POST"
              action="{{ isset($product)
                    ? route('products.update', $product)
                    : route('products.store') }}">

            @csrf
            @isset($product)
                @method('PUT')
            @endisset

            {{-- Title --}}
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title', $product->title ?? '') }}"
                       required>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description"
                          class="form-control"
                          rows="4"
                          required>{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            {{-- Price --}}
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number"
                       name="price"
                       step="0.01"
                       class="form-control"
                       value="{{ old('price', $product->price ?? '') }}"
                       required>
            </div>

            {{-- Available At --}}
            <div class="mb-3">
                <label class="form-label">Available At</label>
                <input type="date"
                       name="available_at"
                       class="form-control"
                       value="{{ old('available_at',
                           isset($product) ? $product->available_at->format('Y-m-d') : '') }}"
                       required>
            </div>

            <button class="btn btn-primary">
                {{ isset($product) ? 'Update' : 'Create' }}
            </button>

        </form>
    </div>
</div>
@endsection
