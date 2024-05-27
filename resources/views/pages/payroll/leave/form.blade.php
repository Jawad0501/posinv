<x-form-modal
    :title="isset($leave) ? 'Update leave':'Add new leave'"
    :action="isset($leave) ? route('payroll.leave.update', $leave->leaveID) : route('payroll.leave.store')"
    :button="isset($leave) ? 'Update':'Submit'"
    id="form"
>
    @isset($leave)
        @method('PUT')
    @endisset
    <input type="hidden" name="employee" value="{{ request()->get('employee') }}">

    <x-form-group name="type" isType="select">
        <option value="">Select leave type</option>
        @foreach ($leaveTypes as $leaveType)
            <option value="{{ $leaveType->leaveTypeID }}" @isset($leave) @selected($leave->leaveTypeID == $leaveType->leaveTypeID) @endisset>{{ $leaveType->scheduleOfAccrual }}</option>
        @endforeach
    </x-form-group>

    <x-form-group
        name="description"
        placeholder="Enter description"
        :value="$leave->description ?? ''"
    />
    <x-form-group
        name="start_date"
        type="date"
        placeholder="Enter start date"
        :value="$leave->startDate ?? ''"
    />
    <x-form-group
        name="end_date"
        type="date"
        placeholder="Enter end date"
        :value="$leave->endDate ?? ''"
    />

    @isset($leave)
    <x-form-group
        name="pay_period"
        type="number"
        placeholder="Enter end date"
        :value="$leave->payPeriod ?? ''"
    />
    @endisset

</x-form-modal>
