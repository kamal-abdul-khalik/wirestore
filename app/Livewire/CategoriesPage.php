<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

class CategoriesPage extends Component
{
    #[Title('Categories')]
    public function render()
    {
        $categories = Category::query()
            ->whereIsActive(true)
            ->get(['id', 'name', 'slug', 'image']);
        return view('livewire.categories-page', compact('categories'));
    }
}
