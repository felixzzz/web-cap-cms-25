@extends('backend.layouts.app')

@section('title', 'Product Flow Visual Builder')

@section('content')
<div class="d-flex flex-column gap-7 gap-lg-10">
    <!-- Header Card -->
    <div class="card card-flush p-5">
        <div class="card-header p-0 pb-4 d-flex align-items-center justify-content-between flex-wrap gap-4">
            <div>
                <h2 class="card-title fw-bold text-gray-900 m-0">Petrochemical Product Flow Visual Builder</h2>
                <div class="text-muted fs-7 mt-1">
                    Atur posisi 26 simpul alur proses kimia (Upstream & Intermediate). Tata letak yang disimpan akan otomatis dikunci dan ditampilkan di halaman publik <strong>Chemical Solutions</strong>.
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="badge {{ $hasCustomLayout ? 'badge-light-success' : 'badge-light-primary' }} fs-7 px-3 py-2">
                    <span class="bullet bullet-dot {{ $hasCustomLayout ? 'bg-success' : 'bg-primary' }} me-2"></span>
                    {{ $hasCustomLayout ? 'Custom Layout Active' : 'Default Preset Active' }}
                </span>

                <a href="{{ $editorUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i> Buka Fullscreen Builder ↗
                </a>
            </div>
        </div>

        <div class="card-body p-0 pt-2">
            <div class="alert alert-dismissible bg-light-info border border-info border-dashed d-flex flex-column flex-sm-row p-4 mb-4">
                <span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-2 mb-sm-0">
                    <i class="fas fa-info-circle fa-2x text-info"></i>
                </span>
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h5 class="mb-1 text-info">Petunjuk Penggunaan Visual Builder:</h5>
                    <span class="fs-7 text-gray-700">
                        1. Geser (drag) simpul-simpul alur produksi ke posisi yang rapi sesuai keinginan.<br>
                        2. Tekan tombol <strong>"💾 Simpan Layout ke CMS"</strong> di toolbar editor.<br>
                        3. Posisi baru akan langsung tersimpan dan di-lock secara proporsional untuk seluruh pengunjung di website.
                    </span>
                </div>
            </div>

            <!-- Embedded Interactive Flow Builder Frame -->
            <div class="position-relative w-100 rounded-2 border border-gray-300 overflow-hidden shadow-xs" style="height: 820px; background: #f8fafc;">
                <iframe 
                    src="{{ $editorUrl }}" 
                    class="w-100 h-100 border-0" 
                    title="Product Flow Visual Builder"
                    allow="clipboard-write"
                ></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

