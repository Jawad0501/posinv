<x-frontend.auth-section>
    <div class="bg-white border border-gray-100 rounded-md p-4">
        <div class="grid grid-cols-1 min-[1800px]:grid-cols-2 gap-4">
            @if ($orders->count())
                @foreach ($orders as $key => $order)
                    @php($image = $order->orderDetails->count() > 0 ? $order->orderDetails->first()?->food?->image : null)

                    <a href="{{ route('order.details', $order->invoice) }}" wire:navigate class="flex flex-col md:flex-row bg-white md:items-center md:justify-between gap-y-4 md:gap-x-3 border border-gray-100 p-2 rounded-md">
                        <div class="flex flex-col md:flex-row">
                            <div class="flex">
                                <img src="{{ uploaded_file($image) }}" alt="Image" srcset="{{ uploaded_file($image) }}" class="rounded-md w-20 object-cover" />
                                <div class="md:hidden ml-2">
                                    <p class="font-medium mb-1">{{ DB::table('food')->whereIn('id', $order->orderDetails?->pluck('food_id'))->pluck('name')->implode(' & ') }}</p>

                                    @if ($order->address !== null && isset($order->address?->location))
                                        <div class="flex items-center mb-1">
                                            <i data-feather="navigation" class="w-3 h-3"></i>
                                            <p class="ml-2 text-xs">{{ $order->address?->location }}</p>
                                        </div>
                                    @endif
                                    <div class="flex flex-col md:flex-row md:items-center gap-y-1 md:gap-x-2">
                                        <div class="flex items-center">
                                            <i data-feather="calendar" class="w-3 h-3"></i>
                                            <p class="ml-2 text-xs">ORDER {{ $order->invoice }}</p>
                                        </div>
                                        <div class="flex items-center lg:ml-3">
                                            <i data-feather="clock" class="w-3 h-3"></i>
                                            <p class="ml-2 text-xs">{{ date('D, M d, H:i A', strtotime($order->created_at)) }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="mt-3 md:mt-0 md:ms-3 hidden md:block">
                                <div>
                                    <p class="font-medium mb-2">{{ DB::table('food')->whereIn('id', $order->orderDetails?->pluck('food_id'))->pluck('name')->implode(' & ') }}</p class="font-medium">
                                    @if ($order->address !== null && isset($order->address?->location))
                                        <div class="flex items-center mb-2">
                                            <i data-feather="navigation" class="w-3 h-3"></i>
                                            <p class="ms-2 text-xs">{{ $order->address?->location }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col md:flex-row md:items-center md:gap-x-2">
                                    <div class="flex items-center">
                                        <i data-feather="calendar" class="w-3 h-3"></i>
                                        <p class="ml-2 text-xs">ORDER {{ $order->invoice }}</p>
                                    </div>
                                    <div class="flex items-center lg:ml-3">
                                        <i data-feather="clock" class="w-3 h-3"></i>
                                        <p class="ml-2 text-xs">{{ date('D, M d, H:i A', strtotime($order->created_at)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right text-sm">
                            @if ($order->delivered_time !== null)
                                <p class="text-success-500">Delivered On {{ date('D, M d, H:i A', strtotime($order->delivered_time)) }}</p>
                            @elseif ($order->status == \App\Enum\OrderStatus::READY->value)
                                @if ($order->delivery_type !== null)
                                    <p class="text-success-500">Ready for {{ $order->delivery_type }}</p>
                                @else
                                    <p class="text-success-500">Order {{ $order->status }}</p>
                                @endif
                            @elseif ($order->status == \App\Enum\OrderStatus::SERVED->value)
                                <p class="text-warning-500">Order {{ $order->status }}</p>
                            @elseif ($order->status == \App\Enum\OrderStatus::PROCESSING->value)
                                <p class="text-warning-500">Your Order has been {{ $order->status }}</p>
                            @elseif ($order->status == \App\Enum\OrderStatus::CANCEL->value)
                                <p class="text-warning-500">Order cancalled</p>
                            @elseif ($order->status == \App\Enum\OrderStatus::PENDING->value)
                                <p class="text-warning-500">Your Order are {{ $order->status }}</p>
                            @endif
                            <p>{{ $order->orderDetails->count() }} items</p>
                        </div>
                    </a>
                @endforeach
            @else

            @endif
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-frontend.auth-section>
