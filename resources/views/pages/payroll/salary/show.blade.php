<x-form-modal title="Employee Details" size="lg">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="25%">Description</th>
                <td>{{ $leave->description }}</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{ format_date($leave->startDate) }}</td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>{{ format_date($leave->endDate) }}</td>
            </tr>
            <tr>
                <th>Periods</th>
                <td>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Number Of Units</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($leave->periods as $period)
                                <tr>
                                    <td>{{ format_date($period->periodStartDate) }}</td>
                                    <td>{{ format_date($period->periodEndDate) }}</td>
                                    <td>{{ $period->numberOfUnits }}</td>
                                    <td>{{ $period->periodStatus }}</td>
                                </tr>
                            @endforeach
                        </tbody>    
                    </table>    
                </td>
            </tr>
        </tbody>
    </table>
</x-form-modal>