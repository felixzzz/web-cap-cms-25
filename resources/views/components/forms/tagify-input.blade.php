@props([
    'type' => 'text',
    'required' => '0',
    'label' => 'labeling',
    'name' => 'naming',
    'placeholder' => '',
    'value' => '',
    'tags' => [],
    'text' => '',
    'hidden' => '0',
])


@php
    $find = [ '[', ']', '-'];
    $replace = [ '.', '', '_'];
    $nameParse = str_replace($find, $replace, $name);
@endphp

<div class="mb-8 fv-row fv-plugins-icon-container" {{ ((bool) $hidden) ? 'style=display:none;' : '' }}>
    <label class="{{ ((bool) $required) ? 'required' : '' }} form-label">{{ $label }}</label>
    <input id="{{$nameParse}}" name="{{$nameParse}}" class="form-control @error($nameParse) is-invalid @enderror mb-2" placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" {{ ((bool) $required) ? 'required' : '' }} />
    <div class="text-muted fs-7">{{ $text }}</div>
    @error($nameParse)
    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
    @enderror
</div>
@push('scripts')
    <script>
        var {{str_slug($name, '_')}} = document.querySelector("#{{str_slug($name, '_')}}");
        new Tagify({{str_slug($name, '_')}}, {
            whitelist: <?php echo str_replace('&quot;', '"', $tags); ?>,
            maxTags: 10,
            dropdown: {
                maxItems: 20,           // <- mixumum allowed rendered suggestions
                classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            }
        });
    </script>
@endpush
