@extends('layouts.app')

@section('title', isset($page) ? 'Update page':'Add New page')

@section('content')

    <div class="col-md-8 my-5 mx-auto">

        <x-page-card :title="isset($page) ? 'Update page':'Add New page'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('frontend.page.index')" title="Back to page" icon="back" />
                @isset($page)
                    <x-card-heading-button :url="route('frontend.page.show', $page->id)" title="page Details" icon="show" />
                @endisset
            </x-slot>

            <form id="form" action="{{isset($page) ? route('frontend.page.update', $page->id) : route('frontend.page.store')}}" method="post" data-redirect="{{ route('frontend.page.index') }}">
                @csrf
                @isset($page)
                    @method('PUT')
                @endisset

                <div class="card-body">

                    <x-form-group
                        name="name"
                        :value="$page->name ?? null"
                        placeholder="Enter page name..."
                    />

                    <x-form-group
                        name="title"
                        isType="textarea"
                        rows="2"
                        placeholder="Enter page title..."
                    >
                        {{ $page->title ?? null }}
                    </x-form-group>

                    <x-form-group
                        name="description"
                        isType="textarea"
                        class="summernote"
                        placeholder="Enter page description..."
                    >
                        {{ $page->description ?? null }}
                    </x-form-group>

                    <x-form-group
                        name="meta_title"
                        isType="textarea"
                        rows="2"
                        placeholder="Enter page meta title..."
                    >
                        {{ $page->meta_title ?? null }}
                    </x-form-group>

                    <x-form-group
                        name="meta_description"
                        isType="textarea"
                        rows="2"
                        placeholder="Enter page meta description..."
                    >
                        {{ $page->meta_description ?? null }}
                    </x-form-group>

                    <x-form-status :status="$page->status ?? true" />

                </div>

                <div class="card-footer text-end">
                    <x-submit-button :text="isset($page) ? 'Update':'Save'" />
                </div>


            </form>

        </x-page-card>
    </div>

@endsection
