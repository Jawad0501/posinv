<div class="tab-pane fade" id="pills-{{ $tabName }}" role="tabpanel" aria-labelledby="pills-{{ $tabName }}-tab">

    <div class="border-bottom px-4 py-2">
        <h4 class="card-title mb-0">OAuth Settings</h4>
    </div>

    <div class="p-4">
        @foreach (['google', 'facebook'] as $name)
            <div class="row">
                <div class="">
                    <h6 class="fs-6">{{ ucfirst($name) }} Creadentials</h6>
                </div>

                <x-form-group name="enable" for="oauth[{{ $name }}][enable]" isType="select" :required="false" column="col-md-6 col-lg-4 col-xxl-3">
                    <option value="1" @selected(config('services.'.$name.'.enable'))>Yes</option>
                    <option value="0" @selected(!config('services.'.$name.'.enable'))>No</option>
                </x-form-group>

                <x-form-group
                    name="ID"
                    for="oauth[{{ $name }}][client_id]"
                    placeholder="Enter id"
                    :value="config('services.'.$name.'.client_id')"
                    :required="false"
                    column="col-md-6 col-lg-4 col-xxl-3"
                />
                <x-form-group
                    name="secret"
                    for="oauth[{{ $name }}][client_secret]"
                    placeholder="Enter secret"
                    :value="config('services.'.$name.'.client_secret')"
                    :required="false"
                    column="col-md-6 col-lg-4 col-xxl-3"
                />
                <x-form-group
                    name="redirect"
                    for="oauth[{{ $name }}][redirect]"
                    placeholder="Enter redirect"
                    :value="config('services.'.$name.'.redirect')"
                    :required="false"
                    column="col-md-6 col-lg-4 col-xxl-3"
                />
            </div>
        @endforeach
    </div>

</div>
