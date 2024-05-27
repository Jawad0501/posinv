<?php

namespace App\Http\Livewire\Frontend\Pages;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

class FAQ extends Component
{
    #[Title('FAQs')]
    public function render()
    {
        $faqs = DB::table('asked_questions')->get();

        return view('livewire.frontend.pages.f-a-q', compact('faqs'));
    }
}
