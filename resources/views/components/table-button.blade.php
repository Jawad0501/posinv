@props(['url', 'type', 'id' => true, 'status' => false])

@php
    if($type == 'delete') $icon = 'fa-trash-can';
    elseif($type == 'show') $icon = 'fa-eye';
    elseif($type == 'clock') $icon = 'fa-clock';
    elseif($type == 'edit') $icon = 'fa-pen-to-square';
    elseif($type == 'qr') $icon = 'fa-qrcode';
    elseif($type == 'invoice') $icon = 'fa-file-invoice';
    else $icon = strtolower($type);
@endphp
<a href="{{ $url }}" id="{{ $id ? "{$type}Btn" : '' }}" class='btn' title="{{ ucfirst($type) }}">
    <i class='fa-solid {{ $icon }}'></i>
</a>
