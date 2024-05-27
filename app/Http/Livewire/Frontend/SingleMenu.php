<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Food;
use Livewire\Component;

class SingleMenu extends Component
{
    public Food $menu;

    public $horizontal = false;

    public function mount(Food $menu)
    {
        $this->menu = $menu;
    }

    public function render()
    {
        return view('livewire.frontend.single-menu');
    }
}
