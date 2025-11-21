@php
    $disk = config('filesystems.default');
    $url = config("filesystems.disks.$disk.url");
    $lang = isset($lang) ? $lang : 'id';
@endphp
<image-component url="{{ $url }}" :field="{{ json_encode($field) }}" value="{{ $value }}"
    component="{{ $component }}" :lang="'{{ $lang }}'"
    :masterlang="{{ $lang === 'id' ? 'true' : 'false' }}"></image-component>
