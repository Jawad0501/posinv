<span
    @class([
        'absolute rounded-full w-4 h-4 text-[10px] leading-4 text-center',
        'left-8 top-1' => $mobile,
        'left-[11px] -top-1' => !$mobile,
        'bg-primary-500 text-white' => $count > 0
    ])
>
    {{ $count > 0 ? $count : '' }}
</span>
