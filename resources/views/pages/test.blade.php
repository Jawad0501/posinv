@extends('layouts.app')

@section('title', 'Supplier')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 col-md-8 offset-md-2 my-5">
        <x-page-card title="Test" route="test" btnTitle="Test">
            
            <div class="table-responsive">
                <table class="table table-sm" id="table">
                    <thead>
                        <tr>
                            <th scope="col">SL No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Reference</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tests as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->reference }}</td>
                                <td>{{ $item->status ? 'Active': 'Disabled' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />

    <script>
        $('#table').DataTable()
    </script>

@endpush
