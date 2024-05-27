<x-frontend.auth-section>
    <div class="bg-white border border-gray-100 rounded-md p-4">

        <div class="col-span-6 ml-2 sm:col-span-4 md:mr-3">
            <!-- Photo File Input -->
            <input type="file" class="hidden" name="image" wire:model="image" id="image" accept="image/*" />
            <div class="text-center">
                <div class="mx-auto w-20 h-20 relative group">
                    <img src="{{ isset($image) ? $image->temporaryUrl() : uploaded_file(auth()->user()->image) }}" class="w-full h-full m-auto rounded-full shadow">
                    <div class="hidden group-hover:block">
                        <label class="absolute w-full h-full rounded-full inset-0 bg-gray-50 flex items-center justify-center transition-all duration-500 cursor-pointer" for="image">
                            <i data-feather="upload" class="w-3 h-3"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <form wire:submit="update" class="mt-6" method="POST">
            <div class="grid md:grid-cols-2 gap-y-3 gap-x-4">
                <x-frontend.form-group label="first_name" wire:model="first_name" placeholder="Enter first name" />
                <x-frontend.form-group label="last_name" wire:model="last_name" placeholder="Enter last name" />
                <x-frontend.form-group label="Email Address" for="email" wire:model="email" type="email" placeholder="Enter email address" />
                <x-frontend.form-group label="Mobile Number" for="phone" wire:model="phone" placeholder="Enter mobile number" />
                <x-frontend.form-group label="date_of_birth" type="date" wire:model="date_of_birth" />
                <x-frontend.form-group label="date_of_anniversary" type="date" wire:model="date_of_anniversary" />
            </div>
            <x-frontend.submit-button label="Save Changes" wire:loading.attr="disabled" wire:target='update' wire:loading.class="btn-loading" />
        </form>
    </div>
</x-frontend.auth-section>
