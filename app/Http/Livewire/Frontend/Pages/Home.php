<?php

namespace App\Http\Livewire\Frontend\Pages;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $categories = DB::table('categories')->get();
        $ads = DB::table('ads')->get();

        return view('livewire.frontend.pages.home', compact('categories', 'ads'));
    }
}
