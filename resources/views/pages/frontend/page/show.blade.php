@extends('layouts.app')

@section('title', 'page Details')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">
        <x-page-card title="page Details">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('frontend.page.index')" title="Back to page" icon="back" />
                @isset($page)
                    <x-card-heading-button :url="route('frontend.page.edit', $page->id)" title="Edit page" icon="edit" />
                @endisset
            </x-slot>

            <div class="card-body mt-3">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="15%">Name</th>
                            <td>{{ $page->name }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td>{{ $page->title }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $page->description }}</td>
                        </tr>
                        <tr>
                            <th>Meta Title</th>
                            <td>{{ $page->meta_title }}</td>
                        </tr>
                        <tr>
                            <th>Meta Description</th>
                            <td>{{ $page->meta_description }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $page->status ? 'Active': 'Disabled'}}</td>
                        </tr>
                    </tbody>

                </table>
            </div>
            
        </x-page-card>
    </div>

@endsection
