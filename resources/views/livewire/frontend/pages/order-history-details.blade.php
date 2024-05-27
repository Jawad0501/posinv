<x-frontend.auth-section>
    <style>
        @media (min-width: 640px) {
            table {
                display: inline-table !important;
            }
            thead tr:not(:first-child) {
                display: none;
            }
        }
        td:not(:last-child) {
            border-bottom: 0;
        }

    </style>
    <div class="bg-white border border-gray-100 rounded-md p-4">

        <div class="fs-14 mb-5">
            <div class="flex justify-between p-2">
                <p>
                    <strong>Table: </strong>
                    @forelse ($order->tables as $key => $table)
                        {{ $key != 0 ? ', ':'' }}{{ $table->table->number }}
                    @empty
                        N/A
                    @endforelse
                </p>
                <p><strong>Order Number: </strong>{{ $order->invoice }}</p>
            </div>
        </div>
        <div class="mb-3">
            <p class="text-center font-bold text-gray"><strong> Order Summary </strong></p>
        </div>

        <table class="w-full flex flex-row flex-no-wrap sm:bg-white rounded-md overflow-hidden sm:shadow my-5 text-xs border">
			<thead class="text-white md:text-black">
                @foreach ($order->orderDetails as $item)
                    <tr class="bg-primary-500 md:bg-transparent flex flex-col flex-no wrap sm:table-row rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                        <th class="p-3 text-left sm:border-b">Menu</th>
                        <th class="p-3 text-left sm:border-b">Addons</th>
                        <th class="p-3 text-left sm:border-b">Price</th>
                        <th class="p-3 text-left sm:border-b">Qty</th>
                        <th class="p-3 text-left sm:border-b">Vat</th>
                        <th class="p-3 text-left sm:border-b">Total</th>
                    </tr>
                @endforeach
			</thead>
			<tbody class="flex-1 sm:flex-none">
                @foreach ($order->orderDetails as $item)
                    <tr class="flex flex-col flex-no wrap sm:table-row mb-2 sm:mb-0 hover:bg-gray-50">
                        <td class="p-3">{{ $item->food?->name }} ({{ $item->variant?->name }})</td>
                        <td class="p-3 truncate">
                            @foreach ($item->addons as $addon)
                                <span>{{ $addon->addon?->name }} {{ !$loop->last ? ',':'' }}</span>
                            @endforeach
                        </td>
                        <td class="p-3">{{ convert_amount($item->price) }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">{{ $item->vat }}%</td>
                        <td class="p-3">{{ convert_amount($item->total_price) }}</td>
                    </tr>
                @endforeach
			</tbody>
		</table>

        {{-- <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-2">Menu name</th>
                        <th scope="col" class="px-4 py-2">Addons</th>
                        <th scope="col" class="px-4 py-2">Price</th>
                        <th scope="col" class="px-4 py-2">Qty</th>
                        <th scope="col" class="px-4 py-2">Vat</th>
                        <th scope="col" class="px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $item)
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                {{ $item->food?->name }} ({{ $item->variant?->name }})
                            </th>
                            <td class="px-4 py-2">
                                @foreach ($item->addons as $addon)
                                    <span class="bg-primary-500 rounded-md text-white p-1 text-xs">{{ $addon->addon?->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-2">{{ convert_amount($item->price) }}</td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                            <td class="px-4 py-2">{{ $item->vat }}%</td>
                            <td class="px-4 py-2">{{ convert_amount($item->total_price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> --}}

        <div class="mt-4">
            <div class="md:w-1/2 ms-auto">
                <div class="flex justify-between">
                    <p class="text-sm"><strong>Order Type:</strong></p>
                    <p>{{ $order->type }}</p>
                </div>
            </div>

            <div class="md:w-1/2 ms-auto">
                <div class="flex justify-between">
                    <p class="text-sm"><strong>Subtotal:</strong></p>
                    <p>{{ convert_amount($order->orderDetails->sum('total_price')) }}</p>
                </div>
            </div>
            <div class="md:w-1/2 ms-auto">
                <div class="flex justify-between">
                    <p class="text-sm"><strong>Discount:</strong></p>
                    <p>-{{ convert_amount($order->discount) }}</p>
                </div>
            </div>
            <div class="md:w-1/2 ms-auto">
                <div class="flex justify-between">
                    <p class="text-sm"><strong>Vat:</strong></p>
                    <p>{{ convert_amount($order->orderDetails->sum('vat')) }}</p>
                </div>
            </div>
            <div class="md:w-1/2 ms-auto">
                <div class="flex justify-between">
                    <p class="text-sm">
                        <strong>
                            {{ $order->payment !== null && $order->payment->status == \App\Enum\PaymentStatus::SUCCESS->value ? 'Paid':'Due' }}
                            Amount:
                        </strong>
                    </p>
                    <p>{{ convert_amount($order->grand_total) }}</p>
                </div>
            </div>
        </div>

    </div>
</x-frontend.auth-section>
