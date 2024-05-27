@extends('layouts.app')

@section('title', 'Setting')

@section('content')

    <div class="col-md-12 mb-5">
        <div class="card">
            <div class="card-body">
                <p class="mb-0">
                    <span class="fw-bold text-primary">@lang('If the data are not changed after you update from this page, please clear the cache from your browser. As we keep the filename the same after the update, it may show the old image for the cache. usually, it works after clear the cache but if you still see the old logo or favicon, it may be caused by server level or network level caching. Please clear them too.')</span>
                    <a href="{{ route('system.cache') }}" class="text-decoration-underline">Clear Cache</a>
                </p>
            </div>
        </div>
    </div>

    @php $col = 'col-md-6 col-lg-4 col-xxl-3'; @endphp

    <div class="col-lg-2">
        <div class="card">
            <div class="card-body p-0">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    @foreach ($data->tabs as $key => $tab)
                        <a href="javascript:void(0)" @class(['tab-link', 'active' => $key == 0]) id="pills-{{ $tab }}-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-{{ $tab }}" type="button" role="tab"
                            aria-controls="pills-{{ $tab }}"
                            aria-selected="true">{{ ucfirst(str_replace('-', ' ', $tab)) }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="card">
            <form id="fileForm" action="{{ route('setting.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="card-body p-0">
                    <div class="tab-content" id="v-pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-{{ $data->tabs[0] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[0] }}-tab">
                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">General Settings</h4>
                            </div>

                            <div class="p-4">
                                <div class="row">
                                    <x-form-group name="app_title" placeholder="Enter app title..." :value="setting('app_title')" :column="$col" />
                                    <x-form-group name="email" type="email" placeholder="Enter email..." :value="setting('email')" :column="$col" :required="false" />
                                    <x-form-group name="phone" placeholder="Enter phone..." :value="setting('phone')" :column="$col" :required="false" />

                                    <x-form-group name="timezone" isType="select" :column="$col" class="select2" :required="false">
                                        <option value="">Select timezone</option>
                                        @foreach ($data->timezones as $timezone)
                                            <option value="{{ $timezone }}" @selected($timezone == setting('timezone'))>{{ $timezone }}</option>
                                        @endforeach
                                    </x-form-group>

                                    <x-form-group name="date_format" isType="select" :column="$col" class="select2" :required="false">
                                        <option value="">Select date format</option>
                                        @foreach ($data->dateFormats as $dateFormat)
                                            <option value="{{ $dateFormat }}" @selected($dateFormat == setting('date_format'))>{{ $dateFormat }}</option>
                                        @endforeach
                                    </x-form-group>

                                    <x-form-group name="address" placeholder="Enter address..." :value="setting('address')"
                                        :column="$col" :required="false" />
                                    <x-form-group name="copyright" placeholder="Enter copyright..." :value="setting('copyright')"
                                        :column="$col" :required="false" />
                                    <!-- <x-form-group name="restaurant_description" placeholder="Enter restaurant description..."
                                        :value="setting('restaurant_description')" :column="$col" :required="false" /> -->

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-{{ $data->tabs[1] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[1] }}-tab">
                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">Finance Settings</h4>
                            </div>
                            <div class="p-4">
                                <div class="row">
                                    <x-form-group name="currency_code" placeholder="Enter currency code" :value="setting('currency_code')" :column="$col" />
                                    <x-form-group name="currency_symbol" isType="select" :column="$col">
                                        @foreach ($data->currencySymbols as $currency_symbol)
                                            <option value="{{ $currency_symbol }}" @selected(setting('currency_symbol') == $currency_symbol)>
                                                {{ $currency_symbol }}
                                            </option>
                                        @endforeach
                                    </x-form-group>
                                    <x-form-group name="currency_position" isType="select" :column="$col">
                                        @foreach (['After Amount', 'Before Amount'] as $currency_position)
                                            <option value="{{ $currency_position }}" @selected(setting('currency_position') == $currency_position)>{{ $currency_position }}</option>
                                        @endforeach
                                    </x-form-group>
                                    <x-form-group name="service_charge" placeholder="Enter service charge" :value="setting('service_charge')" :column="$col" />
                                    <x-form-group name="delivery_charge" placeholder="Enter delivery charge" :value="setting('delivery_charge')" :column="$col" />
                                    <!-- <x-form-group name="opening_purchase_stock" placeholder="Enter opening purchase stock"
                                        :value="setting('opening_purchase_stock')" :column="$col" /> -->
                                    <!-- <x-form-group name="opening_sale_stock" placeholder="Enter opening sale stock"
                                        :value="setting('opening_sale_stock')" :column="$col" />
                                    <x-form-group name="opening_sale_stock" placeholder="Enter opening sale stock"
                                        :value="setting('opening_sale_stock')" :column="$col" /> -->
                                    <x-form-group name="cash_in_hand" placeholder="Enter cash in hand"
                                        :value="setting('cash_in_hand')" :column="$col" />

                                    <!-- <x-form-group name="total_sit_capacity" placeholder="Enter total sit capacity"
                                        :value="setting('total_sit_capacity')" :column="$col" /> -->
                                    <!-- {{-- <x-form-group name="grace_period" question="count hourly" placeholder="Enter grace period"
                                        :value="setting('grace_period')" :column="$col" /> --}} -->
                                    <!-- <x-form-group name="opening_time" placeholder="Enter opening time" :value="setting('opening_time')"
                                        :column="$col" />
                                    <x-form-group name="closing_time" placeholder="Enter closing time" :value="setting('closing_time')"
                                        :column="$col" /> -->
                                    <!-- {{-- <x-form-group name="verify_by" :column="$col" isType="select">
                                        @foreach (['email', 'phone'] as $item)
                                            <option value="{{ $item }}" @selected($item == setting('verify_by'))>
                                                {{ ucfirst($item) }}</option>
                                        @endforeach
                                    </x-form-group> --}} -->
                                </div>
                            </div>
                        </div>

                        {{-- <div class="tab-pane fade" id="pills-{{ $data->tabs[2] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[2] }}-tab">

                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">Email Settings</h4>
                            </div>

                            <div class="p-4">
                                <div class="row">
                                    <x-form-group name="mailer" for="mail[mailer]" isType="select" :column="$col" class="text-uppercase">
                                        @foreach (['smtp', 'sendmail'] as $mailer)
                                            <option value="{{ $mailer }}" @selected(config('mail.mailers.smtp.transport') == $mailer)>{{ $mailer }}</option>
                                        @endforeach
                                    </x-form-group>
                                    <x-form-group name="encryption" for="mail[encryption]" isType="select" :column="$col" class="text-uppercase">
                                        @foreach (['tls', 'ssl'] as $encryption)
                                            <option value="{{ $encryption }}" @selected(config('mail.mailers.smtp.encryption') == $encryption)>{{ $encryption }}</option>
                                        @endforeach
                                    </x-form-group>
                                    <x-form-group name="port" for="mail[port]" isType="select" :column="$col">
                                        @foreach ([465, 587, 2525, 25] as $port)
                                            <option value="{{ $port }}" @selected(config('mail.mailers.smtp.port') == $port)>{{ $port }}</option>
                                        @endforeach
                                    </x-form-group>
                                    <x-form-group name="host" for="mail[host]" placeholder="Enter host" :value="config('mail.mailers.smtp.host')" :column="$col" />
                                    <x-form-group name="username" for="mail[username]" placeholder="Enter username" :value="config('mail.mailers.smtp.username')" :column="$col" />
                                    <x-form-group name="password" type="password" for="mail[password]" placeholder="Enter password" :value="config('mail.mailers.smtp.password')" :column="$col" />
                                    <x-form-group name="from_address" for="mail[from_address]" placeholder="Enter from_address" :value="config('mail.from.address')" :column="$col" />
                                    <x-form-group name="from_name" for="mail[from_name]" placeholder="Enter from name" :value="config('mail.from.name')" :column="$col" />

                                </div>
                            </div>

                        </div> --}}

                        {{-- <div class="tab-pane fade" id="pills-{{ $data->tabs[3] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[3] }}-tab">

                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">Payment Settings</h4>
                            </div>

                            <div class="p-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Stripe Configuation</h6>
                                    </div>

                                    <x-form-group name="enable" for="payment[stripe][enable]" isType="select" :required="false" column="col-md-6 col-lg-4">
                                        <option value="1" @selected(config('services.stripe.enable'))>Yes</option>
                                        <option value="0" @selected(!config('services.stripe.enable'))>No</option>
                                    </x-form-group>

                                    <x-form-group name="key" for="payment[stripe][key]" placeholder="Enter key" :value="config('services.stripe.key')" column="col-md-6 col-lg-4" :required="false" />
                                    <x-form-group name="secret" for="payment[stripe][secret]" placeholder="Enter secret" :value="config('services.stripe.secret')" column="col-md-6 col-lg-4" :required="false" />
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h6>Paypal Configuation</h6>
                                    </div>
                                    <x-form-group name="mode" for="payment[stripe][mode]" isType="select" :required="false"
                                        column="col-md-6 col-lg-3">
                                        @foreach (['sandbox', 'live'] as $mode)
                                            <option value="{{ $mode }}" @selected(config('paypal.mode') == $mode)>
                                                {{ $mode }}</option>
                                        @endforeach
                                    </x-form-group>


                                    <x-form-group name="client_id" for="payment[paypal][client_id]" placeholder="Enter client id"
                                        :value="config('paypal.' . config('paypal.mode') . '.client_id')" column="col-md-6 col-lg-3" :required="false" />
                                    <x-form-group name="client_secret" for="payment[paypal][client_secret]"
                                        placeholder="Enter client secret" :value="config('paypal.' . config('paypal.mode') . '.client_secret')" column="col-md-6 col-lg-3"
                                        :required="false" />
                                    <x-form-group name="enable" for="payment[paypal][enable]" isType="select" :required="false"
                                        column="col-md-6 col-lg-3">
                                        <option value="1" @selected(config('paypal.enable'))>Yes</option>
                                        <option value="0" @selected(!config('paypal.enable'))>No</option>
                                    </x-form-group>
                                </div>
                            </div>
                        </div> --}}

                        {{-- @include('pages.setting.social-login', ['tabName' => $data->tabs[4]]) --}}

                        {{-- <div class="tab-pane fade" id="pills-{{ $data->tabs[5] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[5] }}-tab">
                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">SMS Settings</h4>
                            </div>

                            <div class="p-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Twilio Configuation</h6>
                                    </div>

                                    <x-form-group name="enable" for="sms[twilio][enable]" isType="select" :required="false" column="col-md-6 col-lg-3">
                                        <option value="1" @selected(config('services.twilio.enable'))>Yes</option>
                                        <option value="0" @selected(!config('services.twilio.enable'))>No</option>
                                    </x-form-group>

                                    <x-form-group name="SID" for="sms[twilio][sid]" placeholder="Enter key" :value="config('services.twilio.sid')" column="col-md-6 col-lg-3" :required="false" />
                                    <x-form-group name="auth_token" for="sms[twilio][auth_token]" placeholder="Enter auth token" :value="config('services.twilio.auth_token')" column="col-md-6 col-lg-3" :required="false" />
                                    <x-form-group name="verify SID" for="sms[twilio][verify_sid]" placeholder="Enter verify sid" :value="config('services.twilio.verify_sid')" column="col-md-6 col-lg-3" :required="false" />
                                </div>
                            </div>
                        </div> --}}

                        <div class="tab-pane fade" id="pills-{{ $data->tabs[2] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[2] }}-tab">
                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">File Settings</h4>
                            </div>

                            <div class="p-4">
                                <div class="row">
                                    @php
                                        $items = [
                                            'light_logo' => setting('light_logo'),
                                            'dark_logo' => setting('dark_logo'),
                                            'invoice_logo' => setting('invoice_logo'),
                                            'favicon' => setting('favicon'),
                                            'default_image' => setting('default_image'),
                                        ];
                                    @endphp

                                    @foreach ($items as $key => $item)
                                        @php
                                            $label = match ($key) {
                                                'light_logo' => 'Logo for Dark Background',
                                                'dark_logo' => 'Logo for White Background',
                                                'invoice_logo' => 'Logo for Invoice',
                                                'favicon' => 'Favicon',
                                                'default_image' => 'Defalt Image',
                                            };
                                        @endphp
                                        <div class="{{ $col }}">
                                            <label for="{{ $key }}"
                                                class="mb-2 text-capitalize">{{ $label }}</label>
                                            <div class="card bg-light shadow border-0">
                                                <div class="card-body">
                                                    <img src="{{ uploaded_file($item) }}" id="{{ $key }}"
                                                        width="80px" />
                                                </div>
                                                <div class="card-footer border-0 bg-white">
                                                    <div>
                                                        <input type="file" name="{{ $key }}"
                                                            id="{{ $key }}" data-show-image="{{ $key }}"
                                                            accept="image/*" class="form-control" />
                                                        <div class="invalid-feedback" id="invalid_{{ $key }}"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        {{-- <div class="tab-pane fade" id="pills-{{ $data->tabs[7] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[7] }}-tab">
                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">Hero Section</h4>
                            </div>

                            <div class="p-4">
                                <div class="row">
                                    <x-form-group name="description" for="hero_section_content[description]" isType="textarea" column="col-12" :required="false">
                                        {{ isset($data->hero_section_content?->description) ? $data->hero_section_content?->description : null }}
                                    </x-form-group>

                                    <x-form-group name="heading" for="hero_section_content[heading]" :value="isset($data->hero_section_content?->heading) ? $data->hero_section_content?->heading : null" column="col-sm-6" :required="false" />
                                    <x-form-group name="image" for="hero_section_content[image]" type="file" accept="image/*" data-show-image="show_image_image" column="col-sm-6" :required="false" />
                                    <div class="col-sm-6">
                                        <x-form-group name="image" for="hero_section_content[image]" type="file" accept="image/*" data-show-image="show_image_image" :required="false" />
                                        <div class="text-center mb-5">
                                            <img src="{{ uploaded_file($data->hero_section_content->image) }}" class="img-fluid" id="show_image_image"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> --}}

                        {{-- <div class="tab-pane fade" id="pills-{{ $data->tabs[8] }}" role="tabpanel" aria-labelledby="pills-{{ $data->tabs[8] }}-tab">
                            <div class="border-bottom px-4 py-2">
                                <h4 class="card-title mb-0">Contact Page</h4>
                            </div>

                            <div class="p-4">
                                <div class="row">
                                    <x-form-group
                                        name="address_title"
                                        for="contact_content[address_title]"
                                        :value="$data->contact_content->address_title"
                                        column="col-sm-6"
                                        :required="false"
                                    />
                                    <x-form-group
                                        name="address"
                                        for="contact_content[address]"
                                        :value="$data->contact_content->address"
                                        column="col-sm-6"
                                        :required="false"
                                    />

                                    <x-form-group
                                        name="phone_title"
                                        for="contact_content[phone_title]"
                                        :value="$data->contact_content->phone_title"
                                        column="col-sm-6"
                                        :required="false"
                                    />
                                    <x-form-group
                                        name="phone"
                                        for="contact_content[phone]"
                                        :value="$data->contact_content->phone"
                                        column="col-sm-6"
                                        :required="false"
                                    />

                                    <x-form-group
                                        name="email_title"
                                        for="contact_content[email_title]"
                                        :value="$data->contact_content->email_title"
                                        column="col-sm-6"
                                        :required="false"
                                    />
                                    <x-form-group
                                        name="email"
                                        for="contact_content[email]"
                                        :value="$data->contact_content->email"
                                        column="col-sm-6"
                                        :required="false"
                                    />

                                    <x-form-group
                                        name="support_title"
                                        for="contact_content[support_title]"
                                        :value="$data->contact_content->support_title"
                                        column="col-sm-6"
                                        :required="false"
                                    />
                                    <x-form-group
                                        name="support"
                                        for="contact_content[support]"
                                        :value="$data->contact_content->support"
                                        column="col-sm-6"
                                        :required="false"
                                    />
                                </div>
                            </div>

                        </div> --}}

                    </div>
                </div>

                <div class="card-footer">
                    <x-submit-button text="Update Settings" />
                </div>
            </form>
        </div>
    </div>
@endsection
