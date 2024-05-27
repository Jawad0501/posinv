@props([
    'url' => null,
    'addId' => false,
    'icon' => null,
    'download' => false,
    'title',
])

@php
    if($icon == 'add') $disIcon = 'fa-plus';
    elseif($icon == 'upload') $disIcon = 'fa-upload';
    elseif($icon == 'download') $disIcon = 'fa-download';
    elseif($icon == 'back') $disIcon = 'fa-left-long';
    elseif($icon == 'edit') $disIcon = 'fa-pen-to-square';
    elseif($icon == 'show') $disIcon = 'fa-eye';
    elseif($icon == 'reload') $disIcon = 'fa-rotate';
    elseif($icon == 'print') $disIcon = 'fa-print';
    else $disIcon = '';
@endphp

<a href="{{ $url ? $url : 'javascript:void(0)' }}" {{ $attributes->merge(['class' => 'btn btn-primary text-white text-capitalize', 'id' => $addId ? 'addBtn':'']) }} @if($download) download="download" @endif>
    @if ($icon != null) <i class="fa-solid {{ $disIcon }}"></i> @endif
    {{ $title }}
</a>