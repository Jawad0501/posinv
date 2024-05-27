<x-table :items="['Sl No','Description','Start State','End State','Action']">
    @forelse ($result->leave as $key => $leave)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $leave->description }}</td>
            <td>{{ format_date($leave->startDate) }}</td>
            <td>{{ format_date($leave->endDate) }}</td>
            <td>
                @php($param = '?employee='.request()->get('employee'))
                @include('pages.btn-group', ['data' => [
                    ['url' => route('payroll.leave.show', $leave->leaveID).$param, 'type' => 'show', 'can' => 'show_payroll'],
                    ['url' => route('payroll.leave.edit', $leave->leaveID).$param, 'type' => 'edit', 'can' => 'edit_payroll'],
                    ['url' => route('payroll.leave.destroy', $leave->leaveID).$param, 'type' => 'delete', 'can' => 'delete_payroll']
                ]])  
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">No data available in table</td>
        </tr>
    @endforelse
</x-table>
