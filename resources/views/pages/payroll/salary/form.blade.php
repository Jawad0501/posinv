<x-form-modal
    :title="isset($salary) ? 'Update salary':'Add new salary'"
    :action="isset($salary) ? route('payroll.salary.update', $salary->salaryAndWagesID) : route('payroll.salary.store')"
    :button="isset($salary) ? 'Update':'Submit'"
    id="form"
>
    @isset($salary)
        @method('PUT')
    @endisset
    <input type="hidden" name="employee" value="{{ request()->get('employee') }}">
    <x-form-group name="earning_rate" isType="select">
        <option value="">Select earning rate</option>
        @foreach ($earningsRates as $earningsRate)
            <option value="{{ $earningsRate->earningsRateID }}" @isset($salary) @selected($salary->earningsRateID == $earningsRate->earningsRateID) @endisset>{{ $earningsRate->name }}</option>
        @endforeach
    </x-form-group>
    <x-form-group
        name="unit_per_day"
        type="number"
        placeholder="Enter number of unit per day"
        :value="$salary->numberOfUnitsPerDay ?? ''"
    />
    <x-form-group
        name="unit_per_week"
        type="number"
        placeholder="Enter number of units per week"
        :value="$salary->numberOfUnitsPerWeek ?? ''"
    />
    <x-form-group
        name="annual_salary"
        type="number"
        placeholder="Enter annual salary"
        :value="$salary->annualSalary ?? ''"
    />
    <x-form-group
        name="effective_from"
        type="date"
        placeholder="Enter effective from"
        :value="$salary->effectiveFrom ?? ''"
    />
</x-form-modal>
