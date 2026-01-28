@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Dashboard</h2>
            <p class="text-muted">
                Welcome back, <strong>{{ auth()->user()->name }}</strong>
            </p>
        </div>
    </div>

    <div class="row mt-4">

        {{-- Products Card --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <p class="card-text">
                        Manage product records and inventory.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        View Products
                    </a>
                </div>
            </div>
        </div>

        {{-- Admin Card (future-ready) --}}
        @if (auth()->user()->hasPermission('product.create'))
            <div class="col-md-4">
                <div class="card shadow-sm border-warning">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Admin Area</h5>
                        <p class="card-text">
                            Create, update and delete products.
                        </p>
                        <a href="{{ route('products.create') }}" class="btn btn-warning">
                            Add Product
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
