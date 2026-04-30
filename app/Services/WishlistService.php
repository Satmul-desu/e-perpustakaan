<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;

class WishlistService
{
    public function toggle(User $user, Product $product): array
    {
        if ($user->hasInWishlist($product)) {
            $user->wishlists()->where('product_id', $product->id)->delete();
            return ['added' => false, 'message' => 'Produk dihapus dari wishlist.'];
        }

        $user->wishlists()->create(['product_id' => $product->id]);
        return ['added' => true, 'message' => 'Produk ditambahkan ke wishlist!'];
    }

    public function addToWishlist(User $user, Product $product): bool
    {
        if (!$user->hasInWishlist($product)) {
            $user->wishlists()->create(['product_id' => $product->id]);
            return true;
        }
        return false;
    }

    public function removeFromWishlist(User $user, Product $product): bool
    {
        if ($user->hasInWishlist($product)) {
            $user->wishlists()->where('product_id', $product->id)->delete();
            return true;
        }
        return false;
    }
}