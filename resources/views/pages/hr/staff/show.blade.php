<x-form-modal title="Staff Details" size="lg">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="20%">Name</th>
                <td>{{ $staff->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $staff->email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $staff->phone }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $staff->address }}</td>
            </tr>
            <tr>
                <th>Image</th>
                <td>
                    <img src="{{ uploaded_file($staff->image) }}" alt="{{ $staff->name }}" class="img-fluid">
                </td>
            </tr>

            <tr>
                <th>Status</th>
                <td>{{ $staff->status ? 'Active': 'Disabled' }}</td>
            </tr>
        </tbody>
    </table>
</x-form-modal>
