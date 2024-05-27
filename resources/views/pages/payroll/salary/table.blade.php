<x-table :items="['Sl No', 'No Of Units Per Week', 'Effective From', 'Annual Salary', 'Status', 'Payment Type', 'Action']">
    @forelse ($result->salaryAndWages as $key => $salaryAndWage)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $salaryAndWage->numberOfUnitsPerWeek }}</td>
            <td>{{ format_date($salaryAndWage->effectiveFrom) }}</td>
            <td>{{ convert_amount($salaryAndWage->annualSalary ?? 0 ) }}</td>
            <td>{{ $salaryAndWage->status ?? null }}</td>
            <td>{{ $salaryAndWage->paymentType ?? null }}</td>
            <td>
                @php($param = '?employee='.request()->get('employee'))
                @include('pages.btn-group', ['data' => [
                    ['url' => route('payroll.salary.show', $salaryAndWage->salaryAndWagesID).$param, 'type' => 'show', 'can' => 'show_payroll'],
                    ['url' => route('payroll.salary.edit', $salaryAndWage->salaryAndWagesID).$param, 'type' => 'edit', 'can' => 'edit_payroll'],
                    ['url' => route('payroll.salary.destroy', $salaryAndWage->salaryAndWagesID).$param, 'type' => 'delete', 'can' => 'delete_payroll']
                ]])  
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

