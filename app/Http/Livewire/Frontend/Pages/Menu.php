<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class Menu extends Component
{
    #[Url()]
    public $table_number;

    public $category_no;

    #[Title('Menu')]
    public function render()
    {
        if ($this->table_number && $this->category_no) {
            session()->put('table_number', DB::table('tablelayouts')->where('number', $this->table_number)->value('id'));
        }
        // else {
        //     session()->forget('table_number');
        // }

        $categories = Category::query()->with(['foods' => fn ($q) => $q->visibility()->sellable()])->has('foods')->active()->orderBy('name')->get();

        return view('livewire.frontend.pages.menu', compact('categories'));
    }
}
