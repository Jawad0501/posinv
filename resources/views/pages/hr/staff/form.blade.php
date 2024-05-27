<x-form-modal
    :title="isset($staff) ? 'Update staff':'Add new staff'"
    :action="isset($staff) ? route('staff.update', $staff->id) : route('staff.store')"
    :button="isset($staff) ? 'Update':'Submit'"
    id="fileForm"
    size="lg"
>
    @isset($staff)
        @method('PUT')
    @endisset

    <div class="row">
        <x-form-group
            name="name"
            placeholder="Enter name..."
            :value="$staff->name ?? ''"
            column="col-md-6"
        />

        <x-form-group
            name="email"
            type="email"
            placeholder="Enter email..."
            :value="$staff->email ?? ''"
            column="col-md-6"
        />

        <x-form-group
            name="phone"
            placeholder="Enter phone..."
            :value="$staff->phone ?? ''"
            column="col-md-6"
        />

        <x-form-group name="role" isType="select" column="col-md-6">
            <option value="">Select role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @isset($staff) @selected($role->id == $staff->role_id ? true : false) @endisset>{{ $role->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group
            name="address"
            isType="textarea"
            rows="2"
            placeholder="Enter address..."
            column="col-md-12"
        >
            {{ $staff->address ?? '' }}
        </x-form-group>

        <x-form-group name="image" type="file" :required="isset($staff) ? false : true" accept="image/*" column="col-md-12" />


        @if(!isset($staff))
            <x-form-group
                name="password"
                type="password"
                placeholder="Enter valid password"
                column="col-md-6"
            />

            <x-form-group
                name="confirm password"
                for="password_confirmation"
                type="password"
                placeholder="Enter valid password"
                column="col-md-6"
            />
        @endif

        <div class="col-12">
            <x-form-status :status="$staff->status ?? true" />
        </div>
    </div>


</x-form-modal>
