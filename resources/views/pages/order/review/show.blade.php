<x-form-modal title="Review Details" size="lg">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="20%">Food Name</th>
                <td>{{ $review->food->name }}</td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td>{{ $review->user->full_name }}</td>
            </tr>
            <tr>
                <th>Rating</th>
                <td>{{ $review->rating }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $review->status ? 'Active': 'Disabled' }}</td>
            </tr>
            <tr>
                <th>Comment</th>
                <td>{{ $review->comment }}</td>
            </tr>
        </tbody>
    </table>
</x-form-modal>
