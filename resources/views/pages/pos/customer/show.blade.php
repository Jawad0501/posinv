<x-form-modal title="Customer Details" size="lg">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="20%">Name</th>
                <td>{{ $customer->full_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $customer->email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $customer->phone }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $customer->delivery_address }}</td>
            </tr>
        </tbody>
    </table>
</x-form-modal>
