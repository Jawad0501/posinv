<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Favorite;
use App\Models\Food;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class MenuDetails extends Component
{
    public $show = false;

    public $menu;

    public $variant;

    public $quantity = 1;

    public $addons = [];

    public function render()
    {
        return view('livewire.frontend.menu-details');
    }

    #[On('showModal')]
    public function showModal($slug)
    {
        $this->menu = Food::with('variants', 'addons:id,name,price')->where('slug', $slug)->first();
        if ($this->menu) {
            foreach ($this->menu->addons as $addon) {
                $this->addons[] = [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price,
                    'quantity' => 0,
                ];
            }
            $this->show = true;
        } else {
            $this->dispatch('alert', 'Menu not found', 'error');
        }
    }

    /**
     * close modal
     *
     * @return void
     */
    public function closeModal()
    {
        $this->show = false;
        $this->quantity = 1;
        $this->variant = null;
        $this->addons = [];
    }

    public function quantityUpdate($increment = true, $key = null)
    {
        if ($key === null) {
            if ($increment && $this->quantity >= 1) {
                $this->quantity++;
            }
            if (! $increment && $this->quantity > 1) {
                $this->quantity--;
            }
        } else {
            if ($increment && $this->addons[$key]['quantity'] >= 0) {
                $this->addons[$key]['quantity']++;
            }
            if (! $increment && $this->addons[$key]['quantity'] > 0) {
                $this->addons[$key]['quantity']--;
            }
        }
    }

    /**
     * Add to cart data
     */
    public function addToCart()
    {
        $this->validate([
            'variant' => 'required|integer',
            'quantity' => 'required|integer',
            'addons' => 'nullable|array',
            'addons.id' => 'integer',
            'addons.quantity' => 'integer',
        ]);

        $cart = session()->get('cart');

        $uniqueId = generate_slug("{$this->menu->id} {$this->menu->slug}");

        $variant = $this->menu->variants()->find($this->variant);
        $cart[$uniqueId] = [
            'id' => $this->menu->id,
            'name' => $this->menu->name,
            'slug' => $this->menu->slug,
            'quantity' => $this->quantity,
            'variant_id' => $variant->id ?? null,
            'variant_name' => $variant->name ?? null,
        ];

        $addons = [];
        foreach ($this->addons as $key => $addon) {
            if ($addon['quantity'] > 0) {
                $addons[$key] = [
                    'id' => $addon['id'],
                    'name' => $this->menu->addons()->find($addon['id'])->name,
                    'quantity' => $addon['quantity'],
                ];
            }
        }
        $cart[$uniqueId]['addons'] = $addons;
        session()->put('cart', $cart);

        $this->closeModal();

        $this->dispatch('cart_added');
        $this->dispatch('alert', 'Item added to cart successfully');
    }

    #[On('addToFavorite')]
    public function addToFavorite($slug)
    {
        if (! auth()->check()) {
            $this->dispatch('alert', 'Please at first login your account.', 'error');
        } else {
            $menuId = DB::table('food')->where('slug', $slug)->value('id');
            if ($menuId === null) {
                $this->dispatch('alert', 'Menu not found, please try again.', 'error');
            } else {
                $favorite = Favorite::query()->where('user_id', auth()->id())->where('food_id', $menuId)->first();
                if ($favorite) {
                    $favorite->delete();
                } else {
                    Favorite::query()->create([
                        'user_id' => auth()->id(),
                        'food_id' => $menuId,
                    ]);
                }
                $this->dispatch('favorite_added');
            }
        }
    }
}
