<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Food;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PopularSection extends Component
{
    public $menus;

    public $limit = true;

    public function mount($limit = true)
    {
        $foodIds = OrderDetails::query()->with('food')->select('food_id', DB::raw('SUM(quantity) as popular'))->groupBy('food_id')->orderBy('popular', 'desc')->pluck('food_id');
        // $this->menus = Food::query()->with('allergies:id,image')->whereIn('id', $foodIds)->when($limit, fn($q) => $q->take(6))->active()->latest()->get();
        $this->menus = Food::query()->with('allergies:id,image')->when($limit, fn ($q) => $q->take(6))->active()->visibility()->sellable()->latest()->get();
    }

    public function render()
    {
        return view('livewire.frontend.popular-section');
    }
}
