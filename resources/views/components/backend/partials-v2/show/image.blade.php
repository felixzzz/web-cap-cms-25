@php
$disk = config('filesystems.default');
$url = config("filesystems.disks.$disk.url");
@endphp

<image-show-component
    url="{{ $url }}"
    :field="{{ json_encode($field) }}"
    value="{{ $value }}"
    component="{{ $component }}">
</image-show-component>
