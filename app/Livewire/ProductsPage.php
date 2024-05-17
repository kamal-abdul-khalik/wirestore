<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\{Brand, Category, Product};
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\{Title, Url};
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products')]
class ProductsPage extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url]
    public $selectedCategories = [];
    #[Url]
    public $selectedBrands = [];
    #[Url]
    public $featured;
    #[Url]
    public $onSale;
    #[Url]
    public $priceRange = 0;
    #[Url]
    public $sort = 'latest';

    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)
            ->to(Navbar::class);
        $this->alert('success', 'Product added successfully!', [
            'position' => 'top',
            'timer' => 3000,
            'toast' => true,
            'timerProgressBar' => false,
        ]);
    }

    public function render()
    {
        $productQuery = Product::query()
            ->select('id', 'name', 'images', 'slug', 'price')
            ->whereIsActive(true);

        if (!empty($this->selectedCategories)) {
            $productQuery->whereIn('category_id', $this->selectedCategories);
        }
        if (!empty($this->selectedBrands)) {
            $productQuery->whereIn('brand_id', $this->selectedBrands);
        }
        if ($this->featured) {
            $productQuery->whereIsFeatured(true);
        }
        if ($this->onSale) {
            $productQuery->whereOnSale(true);
        }
        if ($this->priceRange) {
            $productQuery->whereBetween('price', [0, $this->priceRange]);
        }
        if ($this->sort == 'latest') {
            $productQuery->latest();
        }
        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }

        return view('livewire.products-page', [
            'products' => $productQuery->simplePaginate(6),
            'brands' => Brand::whereIsActive(true)->get(['id', 'name', 'slug']),
            'categories' => Category::whereIsActive(true)->get(['id', 'name', 'slug']),
        ]);
    }
}
