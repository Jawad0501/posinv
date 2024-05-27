<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\Food;
use Livewire\Attributes\Title;
use Livewire\Component;

class Favorite extends Component
{
    #[Title('Favorites')]
    public function render()
    {
        $menus = Food::query()->with('allergies:id,image')->whereIn('id', auth()->user()->favorites->pluck('food_id'))->active()->latest()->get();

        return view('livewire.frontend.pages.favorite', compact('menus'));
    }
}
