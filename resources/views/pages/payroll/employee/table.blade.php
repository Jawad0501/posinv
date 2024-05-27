<x-table :items="['Sl No', 'Name', 'Email', 'Phone', 'Gender', 'Action']">
    @forelse ($result->employees as $key => $employee)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $employee->firstName . ' ' . $employee->lastName }}</td>
            <td>{{ $employee->email ?? null }}</td>
            <td>{{ $employee->phoneNumber ?? null }}</td>
            <td>{{ $employee->gender ?? null }}</td>
            <td>
                @include('pages.btn-group', [
                    'data' => [
                        [
                            'url'  => route('payroll.leave.index', ['employee'=> $employee->employeeID]),
                            'type' => 'show',
                            'id'   => false,
                            'can'  => 'show_payroll'
                        ],
                        [
                            'url'  => route('payroll.salary.index', ['employee'=> $employee->employeeID]),
                            'type' => 'show',
                            'id'   => false,
                            'can'  => 'show_payroll'
                        ],
                        [
                            'url'  => route('payroll.employee.show', $employee->employeeID),
                            'type' => 'show',
                            'can'  => 'show_payroll'
                        ],
                        [
                            'url'  => route('payroll.employee.edit', $employee->employeeID),
                            'type' => 'edit',
                            'can'  => 'edit_payroll'
                        ]
                    ],
                ])
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">No data available in table</td>
        </tr>
    @endforelse
</x-table>

<input type="hidden" id="is_datatable" value="{{ $result->pagination->itemCount <= 100 }}">
@if ($result->pagination->itemCount > 100)
    <x-payroll-pagination :pagination="$result->pagination" :total="count($result->employees)" />
@endif

