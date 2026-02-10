@extends('backend.layouts.app')

@section('title', __('Image Component'))

@section('content')
    <x-backend.card>
        <x-slot name="body">
            <form action="{{route('admin.component.image.validation')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card card-flush p-3">
                    <div class="card-body p-3">
                        <div class="form-group">
                            <h1 for="image">Validate Image</h1>
                            <input type="file" name="validate_image" id="validate_image" class="form-control" required>
                            <ul class="small">
                                <li>File type = jpeg,png,jpg,gif,svg</li>
                                <li>Maximal file size = 500 KB</li>
                                <li>Minimum width size = 500 px</li>
                            </ul>
                            @if ($errors->has('validate_image'))
                                <hr>
                                <ul class="small">
                                    @foreach($errors->get('validate_image') as $er)
                                        <li class="text-danger">{{ $er }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card card-flush p-3">
                    <div class="card-body p-3">
                        @if (session('success.validate_image'))
                            <div class="alert alert-success">
                                <p>{{session('success.validate_image')}}</p>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Validate Image</button>
                    </div>
                </div>
            </form>
            <hr>
            <form action="{{route('admin.component.image.resize')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card card-flush p-3">
                    <div class="card-body p-3">
                        <div class="form-group">
                            <h1 for="image">Resize Image</h1>
                            <input type="file" name="resize_image" id="image" class="form-control" required>
                            <ul class="small">
                                <li>File type = jpeg,png,jpg,gif,svg</li>
                                <li>Maximal file size = 500 KB</li>
                            </ul>
                            @if ($errors->has('resize_image'))
                                <hr>
                                <ul class="small">
                                    @foreach($errors->get('resize_image') as $er)
                                        <li class="text-danger">{{ $er }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">Width</label>
                            <input type="number" name="width" value="{{old('width')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Height</label>
                            <input type="number" name="height" value="{{old('height')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maintain Aspect Ratio</label>
                            <input type="checkbox" name="maintain_aspect_ratio" {{old('maintain_aspect_ratio') ? 'checked' : ''}} value="1">
                        </div>
                    </div>
                </div>
                @if (session('success.resize_image'))
                    <div class="card card-flush p-3">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Before Resize</label><br>
                                    <img style="max-height: 250px" class="img-thumbnail" src="{{session('beforeResize')}}"
                                        alt="before resize">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">After Resize</label><br>
                                    <img style="max-height: 250px" class="img-thumbnail" src="{{session('afterResize')}}"
                                        alt="before resize">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card card-flush p-3">
                    <div class="card-body p-3">
                        @if (session('success.resize_image'))
                            <div class="alert alert-success">
                                <p>{{session('success.resize_image')}}</p>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Resize Image</button>
                    </div>
                </div>
            </form>
            <hr>
            <form action="{{route('admin.component.image.crop')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="x" value="" />
                <input type="hidden" name="y" value="" />
                <input type="hidden" name="width" value="" />
                <input type="hidden" name="height" value="" />
                <div class="card card-flush p-3">
                    <div class="card-body p-3">
                        <div class="form-group">
                            <h1 for="image">Crop Image</h1>
                            <input type="file" name="crop_image" id="image" class="form-control" required>
                            <ul class="small">
                                <li>File type = jpeg,png,jpg,gif,svg</li>
                                <li>Maximal file size = 500 KB</li>
                            </ul>
                            @if ($errors->has('crop_image'))
                                <hr>
                                <ul class="small">
                                    @foreach($errors->get('crop_image') as $er)
                                        <li class="text-danger">{{ $er }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card card-flush p-3">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Before Crop</label><br>
                                <img id="previewimage" class="img-thumbnail" src="{{session('beforeCrop', '')}}"
                                    alt="before crop">
                            </div>
                            @if (session('success.crop_image'))
                                <div class="col-6">
                                    <label class="form-label">After Crop</label><br>
                                    <img style="max-height: 250px" class="img-thumbnail" src="{{session('afterCrop')}}"
                                        alt="before crop">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card card-flush p-3">
                    <div class="card-body p-3">
                        @if (session('success.crop_image'))
                            <div class="alert alert-success">
                                <p>{{session('success.crop_image')}}</p>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Crop Image</button>
                    </div>
                </div>
            </form>

        </x-slot>
    </x-backend.card>
@endsection
@push('after-styles')
    <link rel="stylesheet" href="https://unpkg.com/jcrop/dist/jcrop.css">
@endpush
@push('scripts')
    <script src="https://unpkg.com/jcrop"></script>
    <script>
        jQuery(function ($) {
            var p = $("#previewimage");

            $('[name=crop_image]').change(function () {
                var imageReader = new FileReader();
                imageReader.readAsDataURL(this.files[0]);

                imageReader.onload = function (oFREvent) {
                    p.attr('src', oFREvent.target.result).fadeIn();
                    const myImg = document.querySelector("#previewimage");
                    const width = myImg.naturalWidth;
                    const height = myImg.naturalHeight;
                    const newWidth = myImg.clientWidth;
                    const newHeight = myImg.clientHeight;
                    setTimeout(function () {
                        const jcrop = Jcrop.attach('previewimage')
                        jcrop.removeClass('jcrop-image-stage')
                        jcrop.listen('crop.change', (widget, e) => {
                            console.log(widget)
                            $('[name=x]').val(Math.floor(width / newWidth * widget.pos.x));
                            $('[name=y]').val(Math.floor(height / newHeight * widget.pos.y));
                            $('[name=width]').val(Math.floor(height / newHeight * widget.pos.w));
                            $('[name=height]').val(Math.floor(width / newWidth * widget.pos.h));
                        });
                    }, 1000);
                };
            });
        });
    </script>
@endpush