@php
    // Determine if the template supports multilanguage
    $isMultilanguage = $template['multilanguage'] === 'true';

    // Determine the master language from lang_option if multilanguage is enabled
    $masterLang = $isMultilanguage ? collect($lang_option)->firstWhere('master', 'true')['value'] : null;
    $value = '';
@endphp
@if (isset($lang))
    <div class="form-group row {{ $component['keyName'] }}">
        <label class="col-md-2 col-form-label">{{ $component['label'] }}</label>
        <div class="col-md-10">
            @foreach ($component['fields'] as $field)
                @php
                    $fieldName = $component['keyName'] . '[' . $field['name'] . '_' . $lang . ']';
                    $aliasComponent = $component['keyName'] . '_' . $field['name'] . '_' . $lang;
                    $options = $field['options'] ?? [];

                    // Determine if the current language is the master language
                    $isMasterLang = $lang === $masterLang;

                    if (isset($meta[$component['keyName']])) {
                        if (isset($meta[$component['keyName']]->{$field['name'] . '_' . $lang})) {
                            $value = $meta[$component['keyName']]->{$field['name'] . '_' . $lang};
                        } else {
                            $value = '';
                        }
                    }
                @endphp

                @if (View::exists('components.backend.partials-v2.' . $field['type']))
                    @if ($field['type'] === 'image')
                        @if ($isMasterLang)
                            @include('components.backend.partials-v2.' . $field['type'], [
                                'field' => $field,
                                'component' => $fieldName,
                                'nameComponent' => $fieldName,
                                'value' => $value ?? '',
                                'aliasComponent' => $aliasComponent,
                                'loopIndex' => $loopIndex,
                                'section' => $component['keyName'],
                                'postId' => $postId,
                                'options' => $options,
                                'lang' => $lang,
                                'isMasterLang' => $isMasterLang,
                            ])
                        @else
                            <div>
                                <input type="hidden" name="{{ $fieldName }}" value="{{ $value }}" />
                            </div>
                        @endif
                    @else
                        @include('components.backend.partials-v2.' . $field['type'], [
                            'field' => $field,
                            'component' => $fieldName,
                            'nameComponent' => $fieldName,
                            'value' => $value ?? '',
                            'aliasComponent' => $aliasComponent,
                            'loopIndex' => $loopIndex,
                            'section' => $component['keyName'],
                            'postId' => $postId,
                            'options' => $options,
                            'lang' => $lang,
                            'isMasterLang' => $isMasterLang,
                        ])
                    @endif
                @elseif(config('app.env') != 'production')
                    <pre>{!! print_r($field, 1) !!}</pre>
                @endif
            @endforeach
        </div>
    </div>
@else
    <div class="form-group row {{ $component['keyName'] }}">
        <label class="col-md-2 col-form-label">{{ $component['label'] }}</label>
        <div class="col-md-10">
            @foreach ($component['fields'] as $field)
                @php
                    $fieldName = $component['keyName'] . '[' . $field['name'] . ']';
                    $aliasComponent = $component['keyName'] . '_' . $field['name'];
                    $options = $field['options'] ?? [];

                    if (isset($meta[$component['keyName']])) {
                        if (isset($meta[$component['keyName']]->{$field['name']})) {
                            $value = $meta[$component['keyName']]->{$field['name']};
                        } else {
                            $value = '';
                        }
                    }
                @endphp

                @if (View::exists('components.backend.partials-v2.' . $field['type']))
                    @include('components.backend.partials-v2.' . $field['type'], [
                        'field' => $field,
                        'component' => $fieldName,
                        'nameComponent' => $fieldName,
                        'value' => $value ?? '',
                        'aliasComponent' => $aliasComponent,
                        'loopIndex' => $loopIndex,
                        'section' => $component['keyName'],
                        'postId' => $postId,
                        'options' => $options,
                    ])
                @elseif(config('app.env') != 'production')
                    <pre>{!! print_r($field, 1) !!}</pre>
                @endif
            @endforeach
        </div>
    </div>
@endif
