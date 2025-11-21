<div class="form-group">
    <label class="col-form-label">{{ $field['label'] }}</label>
    @if($value || isset($index))
        <img
            @if(isset($index))
                :src="baseUrl + '/' + field.{{ $field['name'] }}"
            @else
                src="{{ asset('storage/'.$value) }}"
            @endif
            class="img-thumbnail d-flex" />
    @endif
    <input
        @if(isset($index))
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
        @endif
        type="file"
        class="filepond form-control"
        name="{{ $component }}"
        accept="image/png, image/jpeg" />
 </div>