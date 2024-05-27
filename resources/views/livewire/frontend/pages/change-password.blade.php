<x-frontend.auth-section>
    <div class="bg-white border border-gray-100 rounded-md p-4">
        <form wire:submit="update" class="mt-6" method="POST">
            <div class="grid md:grid-cols-2 gap-y-3 gap-x-4">
                <x-frontend.form-group label="current_password" type="password" wire:model="current_password" placeholder="Enter current password" />
                <x-frontend.form-group label="new_password" for="password" type="password" wire:model="password" placeholder="Enter new password" />
                <x-frontend.form-group label="confirm_password" for="password_confirmation" type="password" wire:model="password_confirmation" placeholder="Enter confirm password" />
            </div>
            <x-frontend.submit-button label="Save Changes" wire:loading.attr="disabled" wire:loading.class="btn-loading" />
        </form>
    </div>
</x-frontend.auth-section>
