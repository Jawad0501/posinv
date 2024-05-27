<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Attributes\On;
use Livewire\Component;

class FavoriteCount extends Component
{
    public $mobile;

    public function mount($mobile = true)
    {
        $this->mobile = $mobile;
    }

    #[On('favorite_added')]
    public function render()
    {
        $count = auth()->check() ? auth()->user()->favorites->count() : 0;

        return view('livewire.frontend.favorite-count', compact('count'));
    }
}
