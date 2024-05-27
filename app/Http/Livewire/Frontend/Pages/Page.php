<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\Page as ModelsPage;
use Livewire\Component;

class Page extends Component
{
    public $page;

    public function mount($slug)
    {
        $this->page = ModelsPage::query()->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.frontend.pages.page');
    }
}
