<div class="form-group row {{ $component['keyName'] }}">
    <label class="col-md-2 col-form-label">{{ $component['label'] }}</label>
    <div class="col-md-10">
        @foreach($component['fields'] as $field)
            @php
                $fieldName = $component['keyName'] .'_'. $field['name'];
                $options = $field['options'] ?? [];
                $value = isset($meta[$fieldName])? $meta[$fieldName]->value : '';
            @endphp
            @if(View::exists('components.backend.partials.'.$field['type']))
                @include('components.backend.partials.'.$field['type'],
                    [
                        'field' => $field,
                        'component' => $fieldName,
                        'value' => $value,
                        'options' => $options,
                    ]
                )
            @elseif(config('app.env') != 'production')
                <pre>{!! print_r($field,1) !!}</pre>
            @endif
        @endforeach
    </div>
</div>
