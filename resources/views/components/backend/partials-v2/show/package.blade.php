@php
    $oldData = old($component);
    $xdata = '[]';
    $value = ($oldData)? json_encode($oldData) : $value;
    if ($value){
        $json = json_decode($value);
        $xdata = ($json && $value != $json)? $value : '[]';
    }
    $disk = config('filesystems.default');
    $url = config("filesystems.disks.$disk.url");

    $editor_fields = [];
    foreach ($field['list'] as $col) {
        if ($col['type'] == 'editor') {
            $editor_fields[] = $col['name'];
        }
    }

    $locale = null;
    if (isset($lang)) {
        $locale = $lang;
    }
@endphp

<package-component
    url="{{ $url }}"
    :field="{{ json_encode($field) }}"
    value="{{ $xdata }}"
    component="{{ $component }}"
    aliascomponent="{{ $aliasComponent }}"
    section="{{ $section }}"
    postid="{{ $postId }}"
    :editor_fields="{{ json_encode($editor_fields)}}"
    >
</package-component>
