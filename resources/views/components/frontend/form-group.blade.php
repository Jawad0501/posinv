@props([
    'label',
    'islabel'  => true,
    'for'      => null,
    'required' => false,
    'isType'   => 'input',
    'column'   => null,
    'question' => null
])

@php
    $htmlFor = $for != null ? $for : $label;
    $attr    = $attributes->class([
                            'form-control' => $attributes->get('type') !== 'file',
                            'is-invalid' => $errors->has($htmlFor),
                            'block w-full border border-gray-200 focus:shadow-sm dark:focus:shadow-white/10 rounded-sm text-sm focus:z-10 focus:outline-0 focus:border-gray-200 dark:focus:border-white/10 dark:border-white/10 dark:text-white/70
                                      file:bg-transparent file:border-0
                                      file:bg-gray-100 ltr:file:mr-4 rtl:file:ml-4
                                      file:py-3 file:px-4
                                      dark:file:bg-black/20 dark:file:text-white/70' => $attributes->get('type') == 'file',
                        ])
                        ->merge([
                            'type'  => 'text',
                            'name'  => $htmlFor,
                            'id'    => $htmlFor,
                            'value' => $attributes->get('value') ?? old($htmlFor)
                        ]);
@endphp

<div @class([$column => $column != null])>
    @if ($islabel)
        <label for="{{ $htmlFor }}" class="block text-gray-700">
            {{ ucfirst(str_replace('_', ' ', $label)) }}
            @if ($required)
                <span class="text-primary-500">*</span>
            @endif
        </label>
    @endif
    <div class="relative">
        @if ($isType == 'input')
            <input {{ $attr }} @required($required ? true : false) />
        @elseif($isType == 'textarea')
            <textarea {{ $attr }} @required($required ? true : false)>{{ $slot }}</textarea>
        @elseif($isType == 'select')
            <select {{ $attr }} @required($required ? true : false)>
                {{ $slot }}
            </select>
        @else
            {{ $slot }}
        @endif
    </div>
    <small @class(['invalid-feedback text-warning-500', 'hidden' => !$errors->has($htmlFor)]) id="invalid_{{ $htmlFor }}">@error($htmlFor) {{ $message }} @enderror</small>
</div>
