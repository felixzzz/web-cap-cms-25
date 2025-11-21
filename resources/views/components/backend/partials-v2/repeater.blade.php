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
    $editor_simple_fields = [];
    foreach ($field['list'] as $col) {
        if ($col['type'] == 'editor') {
            $editor_fields[] = $col['name'];
        }
        if ($col['type'] == 'editor_simple') {
            $editor_simple_fields[] = $col['name'];
        }
    }

    $locale = null;
    if (isset($lang)) {
        $locale = $lang;
    }
@endphp
@if($locale == 'id' || isset($field['multilang_image']))
    <repeater-component
        url="{{ $url }}"
        locale="{{ $locale }}"
        :field="{{ json_encode($field) }}"
        value="{{ $xdata }}"
        component="{{ $component }}"
        aliascomponent="{{ $aliasComponent }}"
        :editor_fields="{{ json_encode($editor_fields)}}"
        :editor_simple_fields="{{ json_encode($editor_simple_fields)}}"
        >
    </repeater-component>
@elseif($locale != 'id' && isset($field['all_image']))
@else
    <repeater-en-component
        url="{{ $url }}"
        locale="{{ $locale }}"
        :field="{{ json_encode($field) }}"
        value="{{ $xdata }}"
        component="{{ $component }}"
        aliascomponent="{{ $aliasComponent }}"
        :editor_fields="{{ json_encode($editor_fields)}}"
        :editor_simple_fields="{{ json_encode($editor_simple_fields)}}"
    >
    </repeater-en-component>
@endif
