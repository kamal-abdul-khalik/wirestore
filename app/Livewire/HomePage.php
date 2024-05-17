<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    #[Title('Home')]
    public function render()
    {
        $brands = Brand::query()
            ->whereIsActive(true)
            ->take(4)
            ->get(['id', 'name', 'slug', 'image']);

        $categories = Category::query()
            ->whereIsActive(true)
            ->take(4)
            ->get(['id', 'name', 'slug', 'image']);

        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
