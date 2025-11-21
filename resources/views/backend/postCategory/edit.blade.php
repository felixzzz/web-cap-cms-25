@inject('model', '\App\Domains\PostCategory\Models\Category')

@extends('backend.layouts.app')

@section('title', __('Update '))

@section('content')
    <x-forms.patch :action="route('admin.category.update', $category)" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update ')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.category.index',$type)" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div class="row">
                    <div class="col-md-12">
                        <x-forms.text-input name="name" label="Name" required="1" placeholder="The name of category" value="{{$category->name}}"/>
                        <x-forms.text-input name="name_en" label="Name EN" required="1" placeholder="The name of category" value="{{$category->name_en}}"/>
                        @if($type != 'whistleblowing' && $type != 'contact_us')
                        <x-forms.textarea-input name="description" label="Description" required="0" placeholder="" text="" value="{{$category->description}}"/>
                        <x-forms.textarea-input name="description_en" label="Description EN" required="0" placeholder="" text="" value="{{$category->description_en}}"/>
                        @else
                        <x-forms.text-input name="description" label="Email" required="1" placeholder="" text="" value="{{$category->description}}"/>
                        @endif
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-end" type="submit">@lang('Update')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'content' );
    </script>
@endsection
@push('after-scripts')
@endpush
