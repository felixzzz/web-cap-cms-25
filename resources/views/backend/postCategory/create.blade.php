@extends('backend.layouts.app')

@section('title', __('Create Category'))

@section('content')
    <x-forms.post :action="route('admin.category.store',$type)" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Category')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.category.index',$type)"
                              :text="__('Cancel')"/>
            </x-slot>

            <x-slot name="body">
                <div class="row">
                    <div class="col-md-12">

                        <x-forms.text-input name="name" label="Name" required="1" placeholder="The name of category"/>
                        <x-forms.text-input name="name_en" label="Name EN" required="1" placeholder="The name of category"/>
                        @if($type != 'whistleblowing' && $type != 'contact_us')
                        <x-forms.textarea-input name="description" label="Description" required="0" placeholder="" text="" value=""/>
                        <x-forms.textarea-input name="description_en" label="Description EN" required="0" placeholder="" text="" value=""/>
                        @else
                        <x-forms.text-input name="description" label="Email" required="1" placeholder="" text="" value=""/>
                        @endif

                    </div><!--col-md-8-->

                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-end" type="submit">@lang('Create')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>

@endsection
@push('after-scripts')
@endpush
