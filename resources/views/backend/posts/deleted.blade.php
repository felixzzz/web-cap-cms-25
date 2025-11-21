@extends('layouts.base')
@section('title', ucfirst($type))



@section('page')
    <div class="card card-xl-stretch mb-xl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Trashed</span>
            </h3>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body py-3">
            <livewire:backend.post.post-table :post_type="$type" status="deleted"/>
        </div>
    </div>
@endsection

