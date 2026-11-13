<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'category', 'description', 'price', 'stock', 'image', 'is_active'])]
class Product extends Model
{
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = ['formatted_price', 'image_url', 'fallback_image_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope active products.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Format price to Indonesian Rupiah.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Get the Amazon S3 image URL for the product.
     * Exposed in HTML for lab footprinting.
     */
    public function getImageUrlAttribute(): string
    {
        $bucket = env('AWS_BUCKET', config('filesystems.disks.s3.bucket', 'tokokita-assets-lab'));
        $region = env('AWS_DEFAULT_REGION', config('filesystems.disks.s3.region', 'ap-southeast-1'));

        if (! empty($this->image)) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return "https://{$bucket}.s3.{$region}.amazonaws.com/products/{$this->image}";
        }

        return "https://{$bucket}.s3.{$region}.amazonaws.com/products/default.jpg";
    }

    /**
     * Fallback high quality image for when S3 asset is not yet uploaded during lab setup.
     */
    public function getFallbackImageUrlAttribute(): string
    {
        $fallbacks = [
            'Networking' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=600&q=80',
            'Security Tools' => 'https://images.unsplash.com/photo-1614064641938-3bbee52942c7?auto=format&fit=crop&w=600&q=80',
            'Hardware & Server' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80',
            'Aksesoris Komputer' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
            'Laptop & Komputer' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=600&q=80',
        ];

        return $fallbacks[$this->category] ?? 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=600&q=80';
    }

    /**
     * Cart items.
     *
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Order items.
     *
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
