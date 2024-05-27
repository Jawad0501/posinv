@extends('layouts.app')

@section('title', 'Frontend Setting')

@section('content')

    <div class="col-12 my-5">

        <x-page-card title="Frontend Setting">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('dashboard')" title="Back to Dashboard" icon="back"/>
            </x-slot>

            <form id="fileForm" action="{{ route('frontend.setting.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-hero-section-tab" data-bs-toggle="pill" data-bs-target="#pills-hero-section" type="button" role="tab" aria-controls="pills-hero-section" aria-selected="false">hero section</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-hero-section" role="tabpanel" aria-labelledby="pills-hero-section-tab">
                            <div class="row">
                                <x-form-group name="description" for="hero_section_content[description]" isType="textarea" column="col-12" :required="false">
                                    {{ $hero_section_content->description }}
                                </x-form-group>

                                <x-form-group name="heading" for="hero_section_content[heading]" :value="$hero_section_content->heading" column="col-sm-6" :required="false" />

                                <div class="col-sm-6">
                                    <x-form-group name="image" for="hero_section_content[image]" type="file" accept="image/*" data-show-image="show_image_image" :required="false" />
                                    <div class="text-center mb-5">
                                        <img src="{{ uploaded_file($hero_section_content->image) }}" class="img-fluid" id="show_image_image"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="row">
                                <x-form-group
                                    name="address_title"
                                    for="contact_content[address_title]"
                                    :value="$contact_content->address_title"
                                    column="col-sm-6"
                                    :required="false"
                                />
                                <x-form-group
                                    name="address"
                                    for="contact_content[address]"
                                    :value="$contact_content->address"
                                    column="col-sm-6"
                                    :required="false"
                                />

                                <x-form-group
                                    name="phone_title"
                                    for="contact_content[phone_title]"
                                    :value="$contact_content->phone_title"
                                    column="col-sm-6"
                                    :required="false"
                                />
                                <x-form-group
                                    name="phone"
                                    for="contact_content[phone]"
                                    :value="$contact_content->phone"
                                    column="col-sm-6"
                                    :required="false"
                                />

                                <x-form-group
                                    name="email_title"
                                    for="contact_content[email_title]"
                                    :value="$contact_content->email_title"
                                    column="col-sm-6"
                                    :required="false"
                                />
                                <x-form-group
                                    name="email"
                                    for="contact_content[email]"
                                    :value="$contact_content->email"
                                    column="col-sm-6"
                                    :required="false"
                                />

                                <x-form-group
                                    name="support_title"
                                    for="contact_content[support_title]"
                                    :value="$contact_content->support_title"
                                    column="col-sm-6"
                                    :required="false"
                                />
                                <x-form-group
                                    name="support"
                                    for="contact_content[support]"
                                    :value="$contact_content->support"
                                    column="col-sm-6"
                                    :required="false"
                                />
                            </div>
                        </div>

                    </div>


                </div>

                <div class="card-footer text-end p-4">
                    <x-submit-button text="Update"/>
                </div>
            </form>

        </x-page-card>
    </div>

@endsection
