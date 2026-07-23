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
@endphp
<div class="card repeater" x-data="{
        {{ $component }}: {{ $xdata }},
        baseUrl: '{{ $url }}',
        addNewField() {
            this.{{ $component }}.push({});
            let index = this.{{ $component }}.length - 1;
            this.$nextTick(() => {
                @foreach($editor_fields as $editor_field_name)
                    window.ClassicEditor.create(document.querySelector('#{{ $component }}_' + index + '_{{ $editor_field_name }}'));
                @endforeach
            });
        },
        removeField(index) {
            this.{{ $component }}.splice(index, 1);
        }
    }"
    x-init="
        $nextTick(() => {
            for(let index = 0; index < {{ $component }}.length; index++) {
                @foreach($editor_fields as $editor_field_name)
                    window.ClassicEditor.create(document.querySelector('#{{ $component }}_' + index + '_{{ $editor_field_name }}'));
                @endforeach
            }
        });
    ">
    <div class="card-header">
        {{ $field['label'] }}
        <div class="card-header-actions">
            <button type="button" class="btn btn-sm btn-info" @click="addNewField()">+</button>
        </div>
    </div>
    <div class="card-body">
        <template x-for="(field, index) in {{ $component }}" :key="index">
            <div class="row align-items-center">
                <div class="col">
                    @foreach($field['list'] as $index => $one)
                        @if(View::exists('components.backend.partials.'.$one['type']))
                            @php
                                $options = $one['options'] ?? [];
                            @endphp
                            @include(
                                'components.backend.partials.'.$one['type'],
                                [
                                    'field' => $one,
                                    'component' => $component,
                                    'value' => '',
                                    'index' => $index+1,
                                    'options' => $options,
                                ]
                            )
                        @else
                            <pre>{!! print_r($one,1) !!}</pre>
                        @endif
                    @endforeach
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-sm btn-danger btn-small" @click="removeField(index)">&times;</button>
                </div>
            </div>
        </template>
    </div>
</div>

