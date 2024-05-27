<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\Food;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class Search extends Component
{
    #[Url]
    public $search;

    #[Title('Search Menu')]
    #[On('search')]
    public function render()
    {
        $menus = Food::query()->with('allergies:id,image')->whereLike(['name', 'description'], $this->search)->get();

        return view('livewire.frontend.pages.search', ['menus' => $menus]);
    }

    public function submit()
    {
        $this->dispatch('search');
    }
}
