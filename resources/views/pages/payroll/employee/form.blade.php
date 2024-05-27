<x-form-modal
    :title="isset($employee) ? 'Update employee':'Add new employee'"
    :action="isset($employee) ? route('payroll.employee.update', $employee->employeeID) : route('payroll.employee.store')"
    :button="isset($employee) ? 'Update':'Submit'"
    id="form"
    size="lg"
>
    @isset($employee)
        @method('PUT')
    @endisset

    <div class="row">
        <x-form-group
            name="first_name"
            placeholder="Enter first name"
            :value="$employee->firstName ?? ''"
            column="col-md-6"
        />
        <x-form-group
            name="last_name"
            placeholder="Enter last name"
            :value="$employee->lastName ?? ''"
            column="col-md-6"
        />
        {{-- <x-form-group
            name="title"
            placeholder="Enter title"
            :value="$employee->title ?? ''"
            column="col-md-6"
        /> --}}
        <x-form-group name="title" column="col-md-6" isType="select">
            <option value="Mr." @isset($employee) @selected($employee->title == 'Mr.') @endisset>Mr.</option>
            <option value="Ms." @isset($employee) @selected($employee->title == 'Ms.') @endisset>Fs.</option>
        </x-form-group>
        <x-form-group name="gender" column="col-md-6" isType="select">
            <option value="M" @isset($employee) @selected($employee->gender == 'M') @endisset>Male</option>
            <option value="F" @isset($employee) @selected($employee->gender == 'F') @endisset>Female</option>
        </x-form-group>
        <x-form-group
            name="email"
            type="email"
            placeholder="Enter email"
            :value="$employee->email ?? ''"
            column="col-md-6"
        />
        <x-form-group
            name="phone_number"
            placeholder="Enter phone number"
            :value="$employee->phoneNumber ?? ''"
            column="col-md-6"
            max="11"
        />
        <x-form-group
            name="date_of_birth"
            type="date"
            placeholder="Enter date of birth"
            column="col-md-6"
            :value="$employee->dateOfBirth ?? ''"
        />
        <x-form-group
            name="city"
            placeholder="Enter city"
            :value="$employee->address->city ?? ''"
            column="col-md-6"
        />
        <x-form-group
            name="post_code"
            placeholder="Enter post code"
            :value="$employee->address->postCode ?? ''"
            column="col-md-6"
        />
        <x-form-group
            name="address"
            placeholder="Enter address"
            :value="$employee->address->addressLine1 ?? ''"
            column="col-12"
        />
    </div>
</x-form-modal>
