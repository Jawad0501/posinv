@props([
    'label',
    'for' => null
])

<div class="flex items-center">
    <div>
        <input {{ $attributes->merge(['type' => 'checkbox', 'name' => $for !== null ? $for : $label, 'id' => $for !== null ? $for : $label, 'class' => 'opacity-0 absolute h-6 w-6']) }} />
        <div class="bg-white border rounded-md border-gray-100 w-5 h-5 flex justify-center items-center focus-within:border-secondary-500">
            <svg class="fill-current hidden w-3 h-3" version="1.1" viewBox="0 0 17 12" xmlns="http://www.w3.org/2000/svg">
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(-9 -11)" fill="#E56988" fill-rule="nonzero">
                        <path d="m25.576 11.414c0.56558 0.55188 0.56558 1.4439 0 1.9961l-9.404 9.176c-0.28213 0.27529-0.65247 0.41385-1.0228 0.41385-0.37034 0-0.74068-0.13855-1.0228-0.41385l-4.7019-4.588c-0.56584-0.55188-0.56584-1.4442 0-1.9961 0.56558-0.55214 1.4798-0.55214 2.0456 0l3.679 3.5899 8.3812-8.1779c0.56558-0.55214 1.4798-0.55214 2.0456 0z" />
                    </g>
                </g>
            </svg>
        </div>
    </div>
    <label for="{{ $for !== null ? $for : $label }}" class="ml-2 text-sm text-gray-500">
        <span>{{ $label }}</span>
    </label>
</div>
