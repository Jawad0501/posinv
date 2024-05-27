<x-form-modal
    :title="isset($bank) ? 'Update bank':'Add new bank'"
    action="{{ isset($bank) ? route('bank.update', $bank->id) : route('bank.store')}}"
    :button="isset($bank) ? 'Update':'Submit'"
    id="fileForm"
>
    @isset($bank)
        @method('PUT')
    @endisset
    <x-form-group
        name="name"
        placeholder="Bank name"
        :value="$bank->name ?? ''"
    />
    <x-form-group
        name="account_name"
        placeholder="Bank account name"
        :value="$bank->account_name ?? ''"
    />
    <x-form-group
        name="account_number"
        placeholder="Bank account number"
        :value="$bank->account_number ?? ''"
    />
    <x-form-group
        name="branch_name"
        placeholder="Bank branch name"
        :value="$bank->branch_name ?? ''"
    />
    <x-form-group
        name="balance"
        placeholder="Bank balance"
        :value="$bank->balance ?? ''"
    />
    <x-form-group
        name="signature_image"
        type="file"
        :required="false"
        accept="image/*"
        data-show-image="show_signature_image"
    />

    <div class="text-center mb-5">
        <img src="{{ isset($bank) ? uploaded_file($bank->signature_image) : null }}" class="img-fluid" id="show_signature_image"/>
    </div>


</x-form-modal>

