<button data-bs-target="#{{$idModal }}" data-bs-toggle="modal" class="{{$class ?? 'btn btn-sm btn-primary'}}" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    @if (isset($icon)) <i class="{{ $icon }}"></i> @endif {{$label}}
</button>
@push('scripts')
<div class="modal fade" id="{{ $idModal }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <div class="text-center mb-13">
                    <h1 class="mb-3">{{ $title }}</h1>
                    @if ($description != null)
                        <div class="text-muted fw-semibold fs-5">
                            {{ $description }}
                        </div>
                    @endif
                </div>
                {{$slot}}
            </div>
        </div>
    </div>
</div>
@endpush
