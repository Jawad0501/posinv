<?php

namespace App\Http\Livewire\Frontend\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class PopularMenu extends Component
{
    #[Title('Popular Menu')]
    public function render()
    {
        return view('livewire.frontend.pages.popular-menu');
    }
}
