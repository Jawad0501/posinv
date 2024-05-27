<x-form-modal
    :title="'Add New Deposit'"
    action="{{ route('deposit.store')}}"
    :button="'Submit'"
    id="form"
    size="md"
>

    <div class="row">

        <x-form-group
            name="amount"
            placeholder="Amount"
            column="col-12"
        />

        <x-form-group
            name="note"
            placeholder="Note"
            column="col-12"
            :required="false"
        />
    </div>

</x-form-modal>
