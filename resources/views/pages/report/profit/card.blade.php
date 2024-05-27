<table class="table table-striped table-bordered table-hover text-capitalize">
    <tbody>
        <tr>
            <th>
                Opening Stock:
                <br />
                <small class="text-muted">(By purchase price)</small>
            </th>
            <td>{{ convert_amount(setting('opening_purchase_stock'))}}</td>
        </tr>
        <tr>
            <th>
                Opening Stock:
                <br />
                <small class="text-muted">(By sale price)</small>
            </th>
            <td>{{ convert_amount(setting('opening_sale_stock'))}}</td>
        </tr>
        <tr>
            <th>Total Stock Adjustment:</th>
            <td>{{ convert_amount($data['total_stock_adjustments']) }}</td>
        </tr>
        <tr>
            <th>Total Expense:</th>
            <td>{{ convert_amount($data['total_expense']) }}</td>
        </tr>
        <tr>
            <th>Total waste:</th>
            <td>{{ convert_amount($data['total_waste']) }}</td>
        </tr>
        <tr>
            <th>Total purchase:</th>
            <td>{{ convert_amount($data['total_purchase']) }}</td>
        </tr>
        <tr>
            <th>Total Sales:</th>
            <td>{{ convert_amount($data['total_sales']) }}</td>
        </tr>
        <tr>
            <th>Gross Profit:</th>
            <td>{{ convert_amount($data['gross_profit']) }}</td>
        </tr>
        <tr>
            <th>Net Profit:</th>
            <td>{{ convert_amount($data['net_profit']) }}</td>
        </tr>
    </tbody>
</table>