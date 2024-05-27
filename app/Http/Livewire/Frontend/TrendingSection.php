<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Food;
use Livewire\Component;

class TrendingSection extends Component
{
    public $menus;

    public function mount()
    {
        $this->menus = Food::active()->visibility()->sellable()->latest()->take(10)->get();
    }

    public function render()
    {
        return view('livewire.frontend.trending-section');
    }
}
