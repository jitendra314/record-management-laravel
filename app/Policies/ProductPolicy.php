<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * View product list
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('products.view');
    }

    /**
     * View single product
     */
    public function view(User $user, Product $product): bool
    {
        return $user->hasPermission('products.view');
    }

    /**
     * Create product
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('products.create');
    }

    /**
     * Update product
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasPermission('products.update');
    }

    /**
     * Delete product
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasPermission('products.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('products.delete');
    }
}
