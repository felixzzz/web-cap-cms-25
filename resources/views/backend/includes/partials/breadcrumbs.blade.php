@if (Breadcrumbs::has())
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
        @foreach (Breadcrumbs::current() as $crumb)
            @if ($crumb->url() && !$loop->last)
                <li class="breadcrumb-item text-muted">
                    <x-utils.link :href="$crumb->url()" :text="$crumb->title()" class="text-muted text-hover-primary" />
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-200 w-5px h-2px"></span>
                </li>
            @else
                <li class="breadcrumb-item active text-dark">
                    {{ $crumb->title() }}
                </li>
            @endif
        @endforeach
    </ul>
@endif
