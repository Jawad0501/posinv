<x-form-modal
    :title="isset($bankTransaction) ? 'Update bank transaction':'Add new bank transaction'"
    action="{{ isset($bankTransaction) ? route('bank-transaction.update', $bankTransaction->id) : route('bank-transaction.store')}}"
    :button="isset($bankTransaction) ? 'Update':'Submit'"
    id="form"
>
    @isset($bankTransaction)
        @method('PUT')
    @endisset

    <x-form-group name="date" :value="$bankTransaction->date ?? ''" class="datepicker" />

    <x-form-group name="transaction type" for="type" isType="select">
        <option value="">Select type</option>
        @foreach (['creadit', 'debit'] as $type)
            <option value="{{ $type }}" @isset($bankTransaction) @selected($bankTransaction->type == $type ? true:false) @endisset>{{ ucfirst($type) }} {{ $type == 'debit' ? '(+)':'(-)' }}</option>
        @endforeach
    </x-form-group>
    <x-form-group name="bank" isType="select">
        <option value="">Select bank</option>
        @foreach ($banks as $bank)
            <option value="{{ $bank->id }}" @isset($bankTransaction) @selected($bankTransaction->bank_id == $bank->id ? true:false) @endisset>{{ $bank->name }}</option>
        @endforeach
    </x-form-group>

    <x-form-group
        name="withdraw_deposite_id"
        placeholder="Withdraw/Deposite ID"
        :value="$bankTransaction->withdraw_deposite_id ?? ''"
    />
    <x-form-group
        name="amount"
        placeholder="Transaction amount"
        :value="$bankTransaction->amount ?? ''"
    />
    <x-form-group
        name="decsription"
        isType="textarea"
        :required="false"
        rows="2"
        placeholder="Decsription..."
    />


</x-form-modal>

{{-- <div class="modal-header">
    <h5 class="modal-title">{{ isset($bankTransaction) ? 'Update Bank Transaction':'Add new Bank Transaction' }}</h5>
    <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" class="fs-25">&times;</span>
    </button>
</div>

<form id="fileForm" action="{{ isset($bankTransaction) ? route('bank-transaction.update', $bankTransaction->id) : route('bank-transaction.store')}}" method="post">
    @csrf
    @isset($bankTransaction)
        @method('PUT')
    @endisset

    <div class="modal-body">


    </div>

    <div class="modal-footer">
        <x-submit-button :text="isset($bankTransaction) ? 'Update':'Save'" />
    </div>

</form> --}}
