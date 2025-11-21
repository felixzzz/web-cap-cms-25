<hr>
<div class="form-group row {{ $component['keyName'] }}">
    <label class="col-md-2 col-form-label">{{ $component['label'] }}</label>
    <div class="col-md-10">
        @foreach($component['fields'] as $field)
            @php

                $fieldName = $component['keyName'] .'['. $field['name'] .']';
                $aliasComponent = $component['keyName'] .'_'. $field['name'];
                $options = $field['options'] ?? [];

                if (isset($meta[$component['keyName']])) {
                    if (isset($meta[$component['keyName']]->{$field['name']})) {
                        $value = $meta[$component['keyName']]->{$field['name']};
                    } else {
                        $value = '';
                    }
                }

            @endphp
            @if(View::exists('components.backend.partials-v2.show.'.$field['type']))
                @include('components.backend.partials-v2.show.'.$field['type'],
                    [
                        'field' => $field,
                        'component' => $fieldName,
                        'nameComponent' => $fieldName,
                        'value' => $value ?? '',
                        'aliasComponent' => $aliasComponent,
                        'loopIndex' => $loopIndex,
                        'section' =>  $component['keyName'],
                        'postId' =>  $postId,
                        'options' => $options,
                    ]
                )
            @elseif(config('app.env') != 'production')
                <pre>{!! print_r($field,1) !!}</pre>
            @endif
        @endforeach
    </div>
</div>
