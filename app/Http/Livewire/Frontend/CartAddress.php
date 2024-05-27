<?php

namespace App\Http\Livewire\Frontend;

use App\Rules\LocationVerifyRule;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class CartAddress extends Component
{
    public $addressKey = 0;

    public $key;

    public $show = false;

    public $results = [];

    public $type;

    public $location;

    public $update = false;

    public $ispage;

    public function mount($ispage = false)
    {
        $this->ispage = $ispage;
    }

    #[On('address_deleted')]
    public function render()
    {
        return view('livewire.frontend.cart-address');
    }

    /**
     * store new address
     */
    public function submit()
    {
        $this->validate([
            'type' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', new LocationVerifyRule],
        ]);

        $addresss = [];
        $address = auth()->user()->address_book;
        if (! $this->update) {
            if ($address !== null) {
                foreach ($address as $key => $addr) {
                    $addresss[] = [
                        'type' => $addr->type,
                        'location' => $addr->location,
                    ];
                }
            }
            $addresss[] = [
                'type' => $this->type,
                'location' => $this->location,
            ];
        } else {
            foreach ($address as $key => $addr) {
                if ($this->key === $key) {
                    $addresss[] = [
                        'type' => $this->type,
                        'location' => $this->location,
                    ];
                } else {
                    $addresss[] = [
                        'type' => $addr->type,
                        'location' => $addr->location,
                    ];
                }
            }
        }
        auth()->user()->fill(['address_book' => $addresss])->save();

        $this->show = false;
        $this->dispatch('address_deleted');
    }

    /**
     * edit new address
     */
    public function showModal($key = null)
    {
        if ($key !== null) {
            $address = auth()->user()->address_book;
            $this->type = $address[$key]->type;
            $this->location = $address[$key]->location;
            $this->update = true;
            $this->key = $key;
        } else {
            $this->type = 'Home';
            $this->location = '';
            $this->update = false;
        }
        $this->show = true;
    }

    public function updateAddressKey($addressKey)
    {
        $this->addressKey = (int) $addressKey;
        $this->dispatch('updateAddressKey', ['addressKey' => $this->addressKey]);

    }

    public function search()
    {
        $responseArray = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', [
            'input' => $this->location,
            'libraries' => 'places',
            'key' => config('services.google.map_api_key'),
        ])->json();
        $this->results = collect($responseArray['predictions'])->map(fn ($value) => ['id' => $value['place_id'], 'description' => $value['description']]);
    }

    public function setValue($key)
    {
        $this->location = $this->results[$key]['description'];
        $this->results = [];
    }

    /**
     * delete specific address from storage
     */
    public function delete($key)
    {
        $addresss = [];
        $address = auth()->user()->address_book;
        if ($address !== null) {
            foreach ($address as $index => $addr) {
                if ($key != $index) {
                    $addresss[] = [
                        'type' => $addr->type,
                        'location' => $addr->location,
                    ];
                }
            }
        }
        auth()->user()->fill(['address_book' => $addresss])->save();
        $this->dispatch('address_deleted');
    }
}
