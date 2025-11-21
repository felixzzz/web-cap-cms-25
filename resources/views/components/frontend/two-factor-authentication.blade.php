<div>
    @error('code')
        <x-utils.alert type="danger" class="d-flex align-items-center p-5 mb-10">
            {{ $message }}
        </x-utils.alert>
    @enderror

    <form wire:submit.prevent="validateCode" class="form-horizontal">
        <div class="form-group row mb-5">
            <label for="code" class="col-md-4 col-form-label text-md-right">@lang('Authorization Code')</label>

            <div class="col-md-6">
                <input
                    type="text"
                    id="code"
                    wire:model.lazy="code"
                    minlength="6"
                    class="form-control"
                    placeholder="{{ __('Authorization Code') }}"
                    required
                    autofocus />
            </div>
        </div><!--form-group-->

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button class="btn btn-primary" type="submit">@lang('Enable Two Factor Authentication')</button>
            </div>
        </div><!--form-group-->
    </form>
</div>
