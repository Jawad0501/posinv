<span
    @class([
        'absolute rounded-full text-[10px] leading-tight text-center',
        'left-[9px] top-[-9px] bg-white text-primary-500' => $mobile,
        'left-[11px] -top-1 text-white bg-primary-500' => $quantity > 0,
        'w-4 h-4 leading-4' => !$mobile,
    ])
>
    {{ $quantity > 0 ? ($quantity > 9 ? "9+":$quantity) : '' }}
</span>
