<x-form-modal title="Employee Details" size="lg">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="25%">First Name</th>
                <td>{{ $employee->firstName }}</td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td>{{ $employee->lastName }}</td>
            </tr>
            <tr>
                <th>Title</th>
                <td>{{ $employee->title ?? null }}</td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>{{ $employee->gender ?? null }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $employee->email ?? null }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $employee->phoneNumber ?? null }}</td>
            </tr>
            <tr>
                <th>Date of birth</th>
                <td>{{ format_date($employee->dateOfBirth) }}</td>
            </tr>
            <tr>
                <th>Address line 1</th>
                <td>{{ $employee->address->addressLine1 }}</td>
            </tr>
            <tr>
                <th>City</th>
                <td>{{ $employee->address->city }}</td>
            </tr>
            <tr>
                <th>Postcode</th>
                <td>{{ $employee->address->postCode }}</td>
            </tr>
            <tr>
                <th>Country</th>
                <td>{{ $employee->address->countryName }}</td>
            </tr>
            <tr>
                <th>Start date</th>
                <td>{{ format_date($employee->startDate) }}</td>
            </tr>
            <tr>
                <th>Is OffPayroll Worker</th>
                <td>{{ $employee->isOffPayrollWorker ? 'Yes':'No' }}</td>
            </tr>
        </tbody>
    </table>
</x-form-modal>