<div class="sidebar-wrapper " data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ uploaded_file(setting('dark_logo')) }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">{{setting('app_title')}}</h4>
        </div>
        <div class="toggle-icon ms-auto">
            <i class="fa-solid fa-arrow-left-long"></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        @can('show_dashboard')
            <x-nav-link
                name="Dashboard"
                :url="route('dashboard')"
                startUrl="staff/dashboard"
            >
            </x-nav-link>
        @endcan

        <x-nav-link
            name="Products"
            startUrl="staff/master*"
            :items="[
                ['name' => 'Products', 'url' => route('ingredient.index'), 'startUrl' => 'staff/master/ingredient*', 'can' => 'show_ingredient'],
                ['name' => 'Category', 'url' => route('ingredient-category.index'), 'startUrl' => 'staff/master/ingredient-category*', 'can' => 'show_ingredient_category'],
                ['name' => 'Unit', 'url' => route('ingredient-unit.index'), 'startUrl' => 'staff/master/ingredient-unit*', 'can' => 'show_ingredient_unit'],
            ]"
        >
        </x-nav-link>

        @can('show_supplier')
        <x-nav-link
            name="Supplier"
            startUrl="staff/suppliers*"
            :items="[
                ['name' => 'Suppliers', 'url' => route('supplier.index'), 'startUrl' => 'staff/suppliers/supplier*', 'can' => 'show_supplier'],
                ['name' => 'Ledger', 'url' => route('ledger-supplier.index'), 'startUrl' => 'staff/suppliers/ledger*', 'can' => 'show_supplier'],
            ]"
        >
        </x-nav-link>
        @endcan

        @can('show_customer')
        <x-nav-link
            name="Customer"
            startUrl="staff/client*"
            :items="[
                ['name' => 'Customers', 'url' => route('client.customer.index'), 'startUrl' => 'staff/client/customer*', 'can' => 'show_customer'],
                ['name' => 'Ledger', 'url' => route('client.user-ledger.index'), 'startUrl' => 'staff/client/user-ledger*', 'can' => 'show_customer'],
            ]"
        >
        </x-nav-link>
        @endcan

        @can('show_purchase')
        <x-nav-link
                name="Purchases"
                :url="route('purchase.index')"
                startUrl="staff/finance/purchase*"
            >
        </x-nav-link>
        @endcan

        <x-nav-link
            name="Sales"
            startUrl="staff/pos*"
            :items="[
                ['name' => 'POS', 'url' => route('pos.index'), 'startUrl' => 'staff/pos/index*', 'can' => 'show_pos'],
                ['name' => 'Summary', 'url' => route('pos.sales.index'), 'startUrl' => 'staff/pos/sales*', 'can' => 'show_order'],
            ]"
        >
        </x-nav-link>

        <x-nav-link
            name="Expense"
            startUrl="staff/expenses*"
            :items="[
                ['name' => 'Category', 'url' => route('expense-category.index'), 'startUrl' => 'staff/expenses/category*', 'can' => 'show_expense_category'],
                ['name' => 'Expenses', 'url' => route('expense.index'), 'startUrl' => 'staff/exenses/expense*', 'can' => 'show_expense'],
            ]"
        >
        </x-nav-link>

        <!-- <x-nav-link
            name="Finance"
            startUrl="staff/master*"
            :items="[
                ['name' => 'Loss', 'url' => route('ingredient.index'), 'startUrl' => 'staff/master/ingredient*', 'can' => 'show_ingredient'],
                ['name' => 'Profit', 'url' => route('ingredient-category.index'), 'startUrl' => 'staff/master/ingredient-category*', 'can' => 'show_ingredient_category'],
                ['name' => 'Slow Moving Products', 'url' => route('ingredient-category.index'), 'startUrl' => 'staff/master/ingredient-category*', 'can' => 'show_ingredient_category'],
                ['name' => 'Trending Products', 'url' => route('ingredient-category.index'), 'startUrl' => 'staff/master/ingredient-category*', 'can' => 'show_ingredient_category'],
            ]"
        >
        </x-nav-link> -->

        <x-nav-link
            name="Reports"
            startUrl="staff/report*"
            :items="[
                ['name' => 'Purchase Report', 'url' => route('report.purchase'), 'startUrl' => 'staff/report/purchase*', 'can' => 'show_purchase'],
                ['name' => 'Expense Report', 'url' => route('report.expense'), 'startUrl' => 'staff/report/expense*', 'can' => 'show_expense'],
                ['name' => 'Bank Transaction', 'url' => route('report.bank-transaction'), 'startUrl' => 'staff/report/bank-transaction*', 'can' => 'show_bank_transaction'],
                ['name' => 'Product Report', 'url' => route('report.ingredient'), 'startUrl' => 'staff/report/ingredient*', 'can' => 'show_ingredient'],
                ['name' => 'Stock Report', 'url' => route('report.stock'), 'startUrl' => 'staff/report/stock*', 'can' => 'show_stock'],
                ['name' => 'Sale Report', 'url' => route('report.sale'), 'startUrl' => 'staff/report/sale*', 'can' => 'show_stock'],
                ['name' => 'Profit Loss Report', 'url' => route('report.profit.loss'), 'startUrl' => 'staff/report/profit-loss*', 'can' => 'show_stock'],
            ]"
        >
        </x-nav-link>

        <x-nav-link
                name="Product Return"
                :url="route('returns.return.index')"
                startUrl="staff/return"
            >
        </x-nav-link>

        <x-nav-link
            name="Accounting"
            startUrl="staff/accounting*"
            :items="[
                ['name' => 'Deposit', 'url' => route('deposit.index'), 'startUrl' => 'staff/accounting/deposit*', 'can' => 'show_purchase'],
                ['name' => 'Withdraw', 'url' => route('withdraw.index'), 'startUrl' => 'staff/accounting/withdraw*', 'can' => 'show_expense'],
            ]"
        >
        </x-nav-link>

        <x-nav-link
            name="HR"
            startUrl="staff/hr*"
            :items="[
                ['name' => 'Role', 'url' => route('role.index'), 'startUrl' => 'staff/hr/role*', 'can' => 'show_role'],
                ['name' => 'Staff', 'url' => route('staff.index'), 'startUrl' => 'staff/hr/staff*', 'can' => 'show_staff'],
            ]"
        >
        </x-nav-link>

        @can('show_setting')
            <x-nav-link
                name="Setting"
                :url="route('setting.index')"
                startUrl="staff/setting"
                :dropdown="false"
            >
            </x-nav-link>
        @endcan

        <x-nav-link
            name="System"
            startUrl="staff/system*"
            :items="[
                ['name' => 'Cache', 'url' => route('system.cache'), 'startUrl' => 'staff/system/cache*', 'can' => 'show_dashboard'],

            ]"
        >
        </x-nav-link>

        {{-- @can('show_logs')
            <x-nav-link
                name="Logs"
                url="/staff/log-viewer"
                startUrl="staff/log-viewer"
                :dropdown="false"
            >
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 0c53 0 96 43 96 96v3.6c0 15.7-12.7 28.4-28.4 28.4H188.4c-15.7 0-28.4-12.7-28.4-28.4V96c0-53 43-96 96-96zM41.4 105.4c12.5-12.5 32.8-12.5 45.3 0l64 64c.7 .7 1.3 1.4 1.9 2.1c14.2-7.3 30.4-11.4 47.5-11.4H312c17.1 0 33.2 4.1 47.5 11.4c.6-.7 1.2-1.4 1.9-2.1l64-64c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-64 64c-.7 .7-1.4 1.3-2.1 1.9c6.2 12 10.1 25.3 11.1 39.5H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H416c0 24.6-5.5 47.8-15.4 68.6c2.2 1.3 4.2 2.9 6 4.8l64 64c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-63.1-63.1c-24.5 21.8-55.8 36.2-90.3 39.6V240c0-8.8-7.2-16-16-16s-16 7.2-16 16V479.2c-34.5-3.4-65.8-17.8-90.3-39.6L86.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l64-64c1.9-1.9 3.9-3.4 6-4.8C101.5 367.8 96 344.6 96 320H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96.3c1.1-14.1 5-27.5 11.1-39.5c-.7-.6-1.4-1.2-2.1-1.9l-64-64c-12.5-12.5-12.5-32.8 0-45.3z"/></svg>
            </x-nav-link>
        @endcan --}}

    </ul>
    <!--end navigation-->
</div>
