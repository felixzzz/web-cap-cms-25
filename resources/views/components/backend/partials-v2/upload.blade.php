@php
$disk = config('filesystems.default');
$url = config("filesystems.disks.$disk.url");
@endphp

<upload-component
    url="{{ $url }}"
    :field="{{ json_encode($field) }}"
    value="{{ $value }}"
    component="{{ $component }}">
</upload-component>
