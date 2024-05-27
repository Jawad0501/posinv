<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Mail\ContactMail;
use App\Models\Contact as ModelsContact;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;

class Contact extends Component
{
    public $name;

    public $email;

    public $phone;

    public $subject;

    public $message;

    #[Title('Contact')]
    public function render()
    {
        $content = json_decode(setting('contact_content'));

        return view('livewire.frontend.pages.contact', compact('content'));
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->subject = '';
        $this->message = '';
    }

    /**
     * store the spcific customer contact information
     */
    public function submit()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255',
            'phone' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = ModelsContact::query()->create($validated);

        Mail::to($this->email)->send(new ContactMail($contact));

        $this->resetForm();

        $this->dispatch('alert', 'Your contact information has been submitted successfully');
    }
}
