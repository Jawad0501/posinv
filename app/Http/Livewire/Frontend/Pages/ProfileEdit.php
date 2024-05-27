<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public $image;

    public $first_name;

    public $last_name;

    public $email;

    public $phone;

    public $date_of_birth;

    public $date_of_anniversary;

    public function mount()
    {
        $this->first_name = auth()->user()->first_name;
        $this->last_name = auth()->user()->last_name;
        $this->email = auth()->user()->email;
        $this->phone = auth()->user()->phone;
        $this->date_of_birth = auth()->user()->date_of_birth;
        $this->date_of_anniversary = auth()->user()->date_of_anniversary;
    }

    #[Title('Profile Edit')]
    public function render()
    {
        return view('livewire.frontend.pages.profile-edit');
    }

    /**
     * update authenticated user profile
     */
    public function update()
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:50', Rule::unique(User::class, 'phone')->ignore(auth()->id())],
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique(User::class, 'email')->ignore(auth()->id())],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'date_of_anniversary' => ['nullable', 'date_format:Y-m-d'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,bmp,jpeg', 'max:1024'],
            // 'address'  => ['required','string',new LocationVerifyRule],
        ]);

        $validated['image'] = $this->image !== null ? file_upload($this->image, 'customer', auth()->user()->image) : auth()->user()->image;
        User::query()->find(auth()->id())->update($validated);

        $this->dispatch('alert', 'Profile updated successfully');
    }
}
